<?php

namespace YoastCS\Yoast\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\TextStrings;
use YoastCS\Yoast\Utils\PathHelper;
use YoastCS\Yoast\Utils\PathValidationHelper;

/**
 * Check that all mock/doubles OO structures are in a dedicated directory for test fixtures.
 *
 * Additionally, checks that all OO structures in this fixtures directory/directories
 * have `Double` or `Mock` in their name.
 *
 * @since 1.0.0
 * @since 3.0.0 This sniff will no longer check for multiple OO object declarations within one file.
 */
final class TestDoublesSniff implements Sniff {

	/**
	 * Relative paths to the directories where the test doubles/mocks are allowed to be placed.
	 *
	 * The paths should be relative to the root/basepath of the project and can be
	 * customized from within a custom ruleset.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 The property type has changed from string to array.
	 *              Use of this property with a string value has been deprecated.
	 * @since 3.0.0 The default value has changed to an empty array.
	 *              This property will now always need to be set from within a ruleset.
	 *
	 * @var array<string>
	 */
	public $doubles_path = [];

	/**
	 * Validated absolute target paths for test fixture directories or an empty array
	 * if the intended target directory/directories don't exist.
	 *
	 * @var array<string, string>
	 */
	private $target_paths;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		$targets = Tokens::$ooScopeTokens;
		unset( $targets[ \T_ANON_CLASS ] );

		return $targets;
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void|int Void or $stackPtr to the end of the file if no basepath was set
	 *                  or no valid doubles_path(s) were found.
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		// Stripping potential quotes to ensure `stdin_path` passed by IDEs does not include quotes.
		$file = TextStrings::stripQuotes( $phpcsFile->getFileName() );

		if ( $file === 'STDIN' ) {
			return; // @codeCoverageIgnore
		}

		if ( ! isset( $phpcsFile->config->basepath ) ) {
			$phpcsFile->addWarning(
				'For the TestDoubles sniff to be able to function, the --basepath needs to be set.',
				0,
				'MissingBasePath'
			);

			return ( $phpcsFile->numTokens + 1 );
		}

		if ( empty( $this->doubles_path ) ) {
			// Just in case someone would overrule the property with an empty value.
			$phpcsFile->addWarning(
				'Required property "doubles_path" missing. Please edit your custom ruleset to add the property.',
				0,
				'NoDoublesPathProperty'
			);

			return ( $phpcsFile->numTokens + 1 );
		}

		if ( ! isset( $this->target_paths ) || \defined( 'PHP_CODESNIFFER_IN_TESTS' ) ) {
			$this->target_paths = PathValidationHelper::relative_to_absolute( $phpcsFile, $this->doubles_path );
			$this->target_paths = \array_filter( $this->target_paths, 'file_exists' );
			$this->target_paths = \array_filter( $this->target_paths, 'is_dir' );
		}

		$object_name = ObjectDeclarations::getName( $phpcsFile, $stackPtr );
		if ( empty( $object_name ) ) {
			return;
		}

		$name_contains_double_or_mock = false;
		if ( \stripos( $object_name, 'mock' ) !== false || \stripos( $object_name, 'double' ) !== false ) {
			$name_contains_double_or_mock = true;
		}

		$tokens = $phpcsFile->getTokens();
		if ( empty( $this->target_paths ) === true ) {
			if ( $name_contains_double_or_mock === false ) {
				return;
			}

			// Mock/Double class found, but no valid target paths found.
			$data = [
				$tokens[ $stackPtr ]['content'],
				$phpcsFile->config->basepath,
			];

			if ( \count( $this->doubles_path ) === 1 ) {
				$data[] = 'directory';
				$data[] = \implode( '', $this->doubles_path );
			}
			else {
				$all_paths = \implode( '", "', $this->doubles_path );
				$all_paths = \substr_replace( $all_paths, ' and', \strrpos( $all_paths, ',' ), 1 );

				$data[] = 'directories';
				$data[] = $all_paths;
			}

			$phpcsFile->addError(
				'Double/Mock test helper %1$s detected, but no test fixtures sub-%3$s found in "%2$s". Expected: "%4$s". Please create the sub-%3$s.',
				$stackPtr,
				'NoDoublesDirectory',
				$data
			);

			return;
		}

		$path_to_file  = PathHelper::normalize_absolute_path( $file );
		$is_double_dir = false;

		foreach ( $this->target_paths as $target_path ) {
			if ( PathHelper::path_starts_with( $path_to_file, $target_path ) === true ) {
				$is_double_dir = true;
				break;
			}
		}

		$data = [
			$tokens[ $stackPtr ]['content'],
			$object_name,
		];

		if ( $name_contains_double_or_mock === true && $is_double_dir === false ) {
			$phpcsFile->addError(
				'Double/Mock test helpers should be placed in a dedicated test fixtures sub-directory. Found %s: %s',
				$stackPtr,
				'WrongDirectory',
				$data
			);
			return;
		}

		if ( $name_contains_double_or_mock === false && $is_double_dir === true ) {
			$phpcsFile->addError(
				'Double/Mock test helpers should contain "Double" or "Mock" in the class name. Found %s: %s',
				$stackPtr,
				'InvalidClassName',
				$data
			);
		}
	}
}
