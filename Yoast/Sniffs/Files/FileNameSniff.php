<?php

namespace YoastCS\Yoast\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Common;

/**
 * Ensures files comply with the Yoast file name rules.
 *
 * Rules:
 * - (WP) Filenames should be lowercase and words should be separated by dashes (not underscores).
 * - All class files should only contain one class (enforced by another sniff) and the file name
 *   should reflect the class name without the plugin specific prefix.
 * - All interface and trait files should only contain one interface/trait (enforced by another sniff)
 *   and the file name should reflect the interface/trait name without the plugin specific prefix
 *   and with an "-interface" or "-trait" suffix.
 * - Files which don't contain an object structure, but do contain function declarations should
 *   have a "-functions" suffix.
 *
 * @package Yoast\YoastCS
 *
 * @since   0.5
 */
class FileNameSniff implements Sniff {

	/**
	 * List of prefixes for object structures.
	 *
	 * These prefixes do not need to be reflected in the file name.
	 *
	 * Note:
	 * - Prefixes are matched in a case-insensitive manner.
	 * - When several overlapping prefixes match, the longest matching prefix
	 *   will be removed.
	 *
	 * @var string[]
	 */
	public $oo_prefixes = [];

	/**
	 * List of files to exclude from the strict file name check.
	 *
	 * This is used primarily to prevent the sniff from recommending that the
	 * name of the plugin "main" file should be changed as changing that would
	 * deactivate the plugin on upgrade and is therefore not a good idea.
	 *
	 * File names should be provided including the path to the file relative
	 * to the "basepath" known to PHPCS.
	 * File names should not be prefixed with a directory separator.
	 * The list should be provided as a PHPCS array list.
	 *
	 * For this functionality to work with relative file paths - i.e. file paths
	 * from the root of the repository - , the PHPCS `--basepath` config variable
	 * needs to be set. If it is not, a warning will be issued.
	 *
	 * @var string[]
	 */
	public $excluded_files_strict_check = [];

	/**
	 * Object tokens to search for in a file.
	 *
	 * @var (int|string)[]
	 */
	private $oo_tokens = [
		\T_CLASS,
		\T_INTERFACE,
		\T_TRAIT,
	];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return (int|string)[]
	 */
	public function register() {
		return [ \T_OPEN_TAG ];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return int StackPtr to the end of the file, this sniff needs to only
	 *             check each file once.
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		// Stripping potential quotes to ensure `stdin_path` passed by IDEs does not include quotes.
		$file = \preg_replace( '`^([\'"])(.*)\1$`Ds', '$2', $phpcsFile->getFileName() );

		if ( $file === 'STDIN' ) {
			return;
		}

		$path_info = \pathinfo( $file );

		// Basename = filename + extension.
		$basename = '';
		if ( ! empty( $path_info['basename'] ) ) {
			$basename = $path_info['basename'];
		}

		$file_name = '';
		if ( ! empty( $path_info['filename'] ) ) {
			$file_name = $path_info['filename'];
		}

		$extension = '';
		if ( ! empty( $path_info['extension'] ) ) {
			$extension = $path_info['extension'];
		}

		$error      = 'Filenames should be all lowercase with hyphens as word separators. Expected %s, but found %s.';
		$error_code = 'NotHyphenatedLowercase';
		$expected   = \strtolower( \str_replace( '_', '-', $file_name ) );

		if ( $this->is_file_excluded( $phpcsFile, $file ) === false ) {
			$oo_structure = $phpcsFile->findNext( $this->oo_tokens, $stackPtr );
			if ( $oo_structure !== false ) {

				$tokens = $phpcsFile->getTokens();
				$name   = $phpcsFile->getDeclarationName( $oo_structure );

				$prefixes = $this->clean_custom_array_property( $this->oo_prefixes );
				if ( ! empty( $prefixes ) ) {
					// Use reverse natural sorting to get the longest of overlapping prefixes first.
					\rsort( $prefixes, ( \SORT_NATURAL | \SORT_FLAG_CASE ) );
					foreach ( $prefixes as $prefix ) {
						if ( $name !== $prefix && \stripos( $name, $prefix ) === 0 ) {
							$name = \substr( $name, \strlen( $prefix ) );
							$name = \ltrim( $name, '_-' );
							break;
						}
					}
				}

				$expected = \strtolower( \str_replace( '_', '-', $name ) );

				switch ( $tokens[ $oo_structure ]['code'] ) {
					case \T_CLASS:
						$error      = 'Class file names should be based on the class name without the plugin prefix. Expected %s, but found %s.';
						$error_code = 'InvalidClassFileName';
						break;

					case \T_INTERFACE:
						$error      = 'Interface file names should be based on the interface name without the plugin prefix and should have "-interface" as a suffix. Expected "%s", but found "%s".';
						$error_code = 'InvalidInterfaceFileName';

						// Don't duplicate "interface" in the filename.
						if ( \substr( $expected, -10 ) !== '-interface' ) {
							$expected .= '-interface';
						}
						break;

					case \T_TRAIT:
						$error      = 'Trait file names should be based on the trait name without the plugin prefix and should have "-trait" as a suffix. Expected "%s", but found "%s".';
						$error_code = 'InvalidTraitFileName';

						// Don't duplicate "trait" in the filename.
						if ( \substr( $expected, -6 ) !== '-trait' ) {
							$expected .= '-trait';
						}
						break;
				}
			}
			else {
				$has_function = $phpcsFile->findNext( \T_FUNCTION, $stackPtr );
				if ( $has_function !== false && $file_name !== 'functions' ) {
					$error      = 'Files containing function declarations should have "-functions" as a suffix. Expected "%s", but found "%s".';
					$error_code = 'InvalidFunctionsFileName';

					if ( \substr( $expected, -10 ) !== '-functions' ) {
						$expected .= '-functions';
					}
				}
			}
		}

		// Throw the error.
		if ( $expected !== '' && $file_name !== $expected ) {
			$phpcsFile->addError(
				$error,
				0,
				$error_code,
				[
					$expected . '.' . $extension,
					$basename,
				]
			);
		}

		// Only run this sniff once per file, no need to run it again.
		return ( $phpcsFile->numTokens + 1 );
	}

	/**
	 * Check if the file is on the exclude list.
	 *
	 * @param File   $phpcsFile    The file being scanned.
	 * @param string $path_to_file The full path to the file currently being examined.
	 *
	 * @return bool
	 */
	protected function is_file_excluded( File $phpcsFile, $path_to_file ) {
		$exclude = $this->clean_custom_array_property( $this->excluded_files_strict_check, true, true );

		if ( ! empty( $exclude ) ) {
			$exclude      = \array_map( [ $this, 'normalize_directory_separators' ], $exclude );
			$path_to_file = $this->normalize_directory_separators( $path_to_file );

			if ( ! isset( $phpcsFile->config->basepath ) ) {
				$phpcsFile->addWarning(
					'For the exclude property to work with relative file path files, the --basepath needs to be set.',
					0,
					'MissingBasePath'
				);
			}
			else {
				$base_path    = $this->normalize_directory_separators( $phpcsFile->config->basepath );
				$path_to_file = Common::stripBasepath( $path_to_file, $base_path );
			}

			// Lowercase the filename to not interfere with the lowercase/dashes rule.
			$path_to_file = \strtolower( \ltrim( $path_to_file, '/' ) );

			if ( isset( $exclude[ $path_to_file ] ) ) {
				// Filename is on the exclude list.
				return true;
			}
		}

		return false;
	}

	/**
	 * Clean a custom array property received from a ruleset.
	 *
	 * Deals with incorrectly passed custom array properties.
	 * - Remove whitespace surrounding values.
	 * - Remove empty array entries.
	 *
	 * Optionally flips the array to allow for using `isset` instead of `in_array`.
	 *
	 * @param mixed $property The current property value.
	 * @param bool  $flip     Whether to flip the array values to keys.
	 * @param bool  $to_lower Whether to lowercase the array values.
	 *
	 * @return (string|bool)[]
	 */
	protected function clean_custom_array_property( $property, $flip = false, $to_lower = false ) {
		$property = \array_filter( \array_map( 'trim', $property ) );

		if ( $to_lower === true ) {
			$property = \array_map( 'strtolower', $property );
		}

		if ( $flip === true ) {
			$property = \array_fill_keys( $property, false );
		}

		return $property;
	}

	/**
	 * Normalize all directory separators to be a forward slash and remove prefixed slash.
	 *
	 * @param string $path Path to normalize.
	 *
	 * @return string
	 */
	private function normalize_directory_separators( $path ) {
		return \ltrim( \strtr( $path, '\\', '/' ), '/' );
	}
}
