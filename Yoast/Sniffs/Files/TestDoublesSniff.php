<?php
/**
 * YoastCS\Yoast\Sniffs\Files\TestDoublesSniff.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace YoastCS\Yoast\Sniffs\Files;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Check that all mock/doubles classes are in their own file and in a `doubles` directory.
 *
 * @package Yoast\YoastCS
 *
 * @since   1.0.0
 */
class TestDoublesSniff implements Sniff {

	/**
	 * Relative path to the directory where the test doubles/mocks should be placed.
	 *
	 * The path should be relative to the root/basepath of the project and can be
	 * customized from within a custom ruleset.
	 *
	 * @var string
	 */
	public $doubles_path = '/tests/doubles';

	/**
	 * Target path for test double/mock classes or false if the intended
	 * target directory doesn't exist.
	 *
	 * @var string|bool
	 */
	protected $target_path;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
		);
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current
	 *                                               in the stack passed in $tokens.
	 *
	 * @return void|int Void or StackPtr to the end of the file if no basepath was set.
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		// Stripping potential quotes to ensure `stdin_path` passed by IDEs does not include quotes.
		$file = preg_replace( '`^([\'"])(.*)\1$`Ds', '$2', $phpcsFile->getFileName() );

		if ( 'STDIN' === $file ) {
			return;
		}

		$object_name = $phpcsFile->getDeclarationName( $stackPtr );
		if ( empty( $object_name ) ) {
			return;
		}

		if ( stripos( $object_name, 'mock' ) === false && stripos( $object_name, 'double' ) === false ) {
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

		$base_path = $this->normalize_directory_separators( $phpcsFile->config->basepath );
		if ( ! isset( $this->target_path ) || defined( 'PHP_CODESNIFFER_IN_TESTS' ) ) {
			$target_path  = $base_path . '/';
			$target_path .= ltrim( $this->normalize_directory_separators( $this->doubles_path ), '/' );

			$this->target_path = false;
			if ( file_exists( $target_path ) && is_dir( $target_path ) ) {
				$this->target_path = strtolower( $target_path );
			}
		}

		if ( false === $this->target_path ) {
			// Non-existent target path.
			$phpcsFile->addError(
				'Double/Mock test helper class detected, but no "%s" sub-directory found in "%s". Please create the sub-directory.',
				$stackPtr,
				'NoDoublesDirectory',
				array(
					$this->doubles_path,
					$base_path,
				)
			);
		}

		$path_info = pathinfo( $file );
		if ( empty( $path_info['dirname'] ) ) {
			return;
		}

		$tokens  = $phpcsFile->getTokens();
		$dirname = $this->normalize_directory_separators( $path_info['dirname'] );
		if ( false === $this->target_path || stripos( $dirname, $this->target_path ) === false ) {
			$phpcsFile->addError(
				'Double/Mock test helper classes should be placed in the "%s" sub-directory. Found %s: %s',
				$stackPtr,
				'WrongDirectory',
				array(
					$this->doubles_path,
					$tokens[ $stackPtr ]['content'],
					$object_name,
				)
			);
		}

		$more_objects_in_file = $phpcsFile->findNext( $this->register(), ( $stackPtr + 1 ) );
		if ( false !== $more_objects_in_file ) {
			$phpcsFile->addError(
				'Double/Mock test helper classes should be in their own file. Found %s: %s',
				$stackPtr,
				'OneObjectPerFile',
				array(
					$tokens[ $stackPtr ]['content'],
					$object_name,
				)
			);
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
		return strtr( $path, '\\', '/' );
	}
}
