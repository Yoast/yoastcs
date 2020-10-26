<?php

namespace YoastCS\Yoast\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Check that all mock/doubles classes are in their own file and in a `doubles` directory.
 *
 * Additionally, checks that all classes in the `doubles` directory/directories
 * have `Double` or `Mock` in the class name.
 *
 * @package Yoast\YoastCS
 *
 * @since   1.0.0
 */
class TestDoublesSniff implements Sniff {

	/**
	 * Relative paths to the directories where the test doubles/mocks are allowed to be placed.
	 *
	 * The paths should be relative to the root/basepath of the project and can be
	 * customized from within a custom ruleset.
	 *
	 * Preferably only one path is provided per project, but in exceptional circumstances
	 * multiple paths can be allowed.
	 *
	 * The new PHPCS 3.4.0 array `extend` feature can be used to add to this list.
	 * To overrule the list, just set the property.
	 * {@link https://github.com/squizlabs/PHP_CodeSniffer/pull/2154}
	 *
	 * @since 1.0.0
	 * @since 1.1.0 The property type has changed from string to array.
	 *              Use of this property with a string value has been deprecated.
	 *
	 * @var string[]
	 */
	public $doubles_path = [
		'/tests/doubles',
	];

	/**
	 * Validated absolute target paths for test double/mock classes or an empty array
	 * if the intended target directory/directories don't exist.
	 *
	 * @var string[]
	 */
	protected $target_paths;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return (int|string)[]
	 */
	public function register() {
		return [
			\T_CLASS,
			\T_INTERFACE,
			\T_TRAIT,
		];
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
		$file = \preg_replace( '`^([\'"])(.*)\1$`Ds', '$2', $phpcsFile->getFileName() );

		if ( $file === 'STDIN' ) {
			return;
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

		/*
		 * BC-compatibility for when the property was still a string.
		 *
		 * {@internal This should be removed in YoastCS 2.0.0.}}
		 */
		if ( \is_string( $this->doubles_path ) ) {
			$this->doubles_path = (array) $this->doubles_path;
		}

		$tokens    = $phpcsFile->getTokens();
		$base_path = $this->normalize_directory_separators( $phpcsFile->config->basepath );
		$base_path = \rtrim( $base_path, '/' ) . '/'; // Make sure the base_path ends in a single slash.

		if ( ! isset( $this->target_paths ) || \defined( 'PHP_CODESNIFFER_IN_TESTS' ) ) {
			$this->target_paths = [];

			foreach ( $this->doubles_path as $doubles_path ) {
				$target_path  = $base_path;
				$target_path .= \trim( $this->normalize_directory_separators( $doubles_path ), '/' ) . '/';

				if ( \file_exists( $target_path ) && \is_dir( $target_path ) ) {
					$this->target_paths[] = \strtolower( $target_path );
				}
			}
		}

		$object_name = $phpcsFile->getDeclarationName( $stackPtr );
		if ( empty( $object_name ) ) {
			return;
		}

		$name_contains_double_or_mock = false;
		if ( \stripos( $object_name, 'mock' ) !== false || \stripos( $object_name, 'double' ) !== false ) {
			$name_contains_double_or_mock = true;
		}


		if ( empty( $this->target_paths ) === true ) {
			if ( $name_contains_double_or_mock === true ) {
				// No valid target paths found.
				$data = [
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
					'Double/Mock test helper class detected, but no test doubles sub-%2$s found in "%1$s". Expected: "%3$s". Please create the sub-%2$s.',
					$stackPtr,
					'NoDoublesDirectory',
					$data
				);
			}
		}
		else {
			$path_to_file  = $this->normalize_directory_separators( $file );
			$is_double_dir = false;

			foreach ( $this->target_paths as $target_path ) {
				if ( \stripos( $path_to_file, $target_path ) !== false ) {
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
					'Double/Mock test helper classes should be placed in a dedicated test doubles sub-directory. Found %s: %s',
					$stackPtr,
					'WrongDirectory',
					$data
				);
			}
			elseif ( $name_contains_double_or_mock === false && $is_double_dir === true ) {
				$phpcsFile->addError(
					'Double/Mock test helper classes should contain "Double" or "Mock" in the class name. Found %s: %s',
					$stackPtr,
					'InvalidClassName',
					$data
				);
			}
		}

		if ( $name_contains_double_or_mock === true ) {
			$more_objects_in_file = $phpcsFile->findNext( $this->register(), ( $stackPtr + 1 ) );
			if ( $more_objects_in_file === false ) {
				$more_objects_in_file = $phpcsFile->findPrevious( $this->register(), ( $stackPtr - 1 ) );
			}

			if ( $more_objects_in_file !== false ) {
				$data = [
					$tokens[ $stackPtr ]['content'],
					$object_name,
					$tokens[ $more_objects_in_file ]['content'],
					$phpcsFile->getDeclarationName( $more_objects_in_file ),
				];

				$phpcsFile->addError(
					'Double/Mock test helper classes should be in their own file. Found %1$s: %2$s and %3$s: %4$s',
					$stackPtr,
					'OneObjectPerFile',
					$data
				);
			}
		}
	}

	/**
	 * Normalize all directory separators to be a forward slash.
	 *
	 * @param string $path Path to normalize.
	 *
	 * @return string
	 */
	private function normalize_directory_separators( $path ) {
		return \strtr( $path, '\\', '/' );
	}
}
