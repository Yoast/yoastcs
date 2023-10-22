<?php

namespace YoastCS\Yoast\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\ObjectDeclarations;
use PHPCSUtils\Utils\TextStrings;

/**
 * Ensures files comply with the Yoast file name rules.
 *
 * Rules:
 * - (WP) Filenames should be lowercase and words should be separated by dashes (not underscores).
 * - All class files should only contain one class (enforced by another sniff) and the file name
 *   should reflect the class name without the plugin specific prefix.
 * - All interface, trait and enum files should only contain one interface/trait/enum (enforced by another sniff)
 *   and the file name should reflect the interface/trait/enum name without the plugin specific prefix
 *   and with an "-interface", "-trait" or "-enum" suffix.
 * - Files which don't contain an object structure, but do contain function declarations should
 *   have a "-functions" suffix.
 *
 * @since 0.5
 * @since 3.0.0 The sniff will now also be enforced for files only using the PHP short open tag.
 */
final class FileNameSniff implements Sniff {

	/**
	 * Object tokens to search for in a file.
	 *
	 * @var array<int|string>
	 */
	private const NAMED_OO_TOKENS = [
		\T_CLASS,
		\T_ENUM,
		\T_INTERFACE,
		\T_TRAIT,
	];

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
	 * @var array<string>
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
	 * @var array<string>
	 */
	public $excluded_files_strict_check = [];

	/**
	 * Cache of previously set OO prefixes.
	 *
	 * Prevents having to do the same prefix validation over and over again.
	 *
	 * @var array<string>
	 */
	private $previous_oo_prefixes = [];

	/**
	 * Validated & cleaned up OO set prefixes.
	 *
	 * @var array<string>
	 */
	private $clean_oo_prefixes = [];

	/**
	 * Cache of previously set list of excluded files.
	 *
	 * Prevents having to do the same file validation over and over again.
	 *
	 * @var array<string>
	 */
	private $previous_excluded_files = [];

	/**
	 * Validated & cleaned up list of absolute paths to the excluded files.
	 *
	 * @var array<string, int> Key is the path, value irrelevant.
	 */
	private $validated_excluded_files = [];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		return Collections::phpOpenTags();
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
		$file = TextStrings::stripQuotes( $phpcsFile->getFileName() );

		if ( $file === 'STDIN' ) {
			return ( $phpcsFile->numTokens + 1 ); // @codeCoverageIgnore
		}

		// Respect phpcs:disable comments as long as they are not accompanied by an enable.
		$tokens = $phpcsFile->getTokens();
		$i      = -1;
		while ( $i = $phpcsFile->findNext( \T_PHPCS_DISABLE, ( $i + 1 ) ) ) {
			if ( empty( $tokens[ $i ]['sniffCodes'] )
				|| isset( $tokens[ $i ]['sniffCodes']['Yoast'] )
				|| isset( $tokens[ $i ]['sniffCodes']['Yoast.Files'] )
				|| isset( $tokens[ $i ]['sniffCodes']['Yoast.Files.FileName'] )
			) {
				do {
					$i = $phpcsFile->findNext( \T_PHPCS_ENABLE, ( $i + 1 ) );
				} while ( $i !== false
					&& ! empty( $tokens[ $i ]['sniffCodes'] )
					&& ! isset( $tokens[ $i ]['sniffCodes']['Yoast'] )
					&& ! isset( $tokens[ $i ]['sniffCodes']['Yoast.Files'] )
					&& ! isset( $tokens[ $i ]['sniffCodes']['Yoast.Files.FileName'] )
				);

				if ( $i === false ) {
					// The entire (rest of the) file is disabled.
					return ( $phpcsFile->numTokens + 1 );
				}
			}
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

		$error      = 'Filenames should be all lowercase with hyphens as word separators.';
		$error_code = 'NotHyphenatedLowercase';
		$expected   = \strtolower( \preg_replace( '`[[:punct:]]`', '-', $file_name ) );

		if ( $this->is_file_excluded( $phpcsFile, $file ) === false ) {
			$oo_structure = $phpcsFile->findNext( self::NAMED_OO_TOKENS, $stackPtr );
			if ( $oo_structure !== false ) {

				$oo_name = ObjectDeclarations::getName( $phpcsFile, $oo_structure );

				if ( ! empty( $oo_name ) ) {
					$this->validate_oo_prefixes();
					if ( ! empty( $this->clean_oo_prefixes ) ) {
						foreach ( $this->clean_oo_prefixes as $prefix ) {
							if ( $oo_name !== $prefix && \stripos( $oo_name, $prefix ) === 0 ) {
								$oo_name = \substr( $oo_name, \strlen( $prefix ) );
								$oo_name = \ltrim( $oo_name, '_-' );
								break;
							}
						}
					}

					$expected = \strtolower( \str_replace( '_', '-', $oo_name ) );

					switch ( $tokens[ $oo_structure ]['code'] ) {
						case \T_CLASS:
							$error      = 'Class file names should be based on the class name without the plugin prefix.';
							$error_code = 'InvalidClassFileName';
							break;

						// Interfaces, traits, enums.
						default:
							$oo_type         = \strtolower( $tokens[ $oo_structure ]['content'] );
							$oo_type_ucfirst = \ucfirst( $oo_type );

							$error      = \sprintf(
								'%1$s file names should be based on the %2$s name without the plugin prefix and should have "-%2$s" as a suffix.',
								$oo_type_ucfirst,
								$oo_type
							);
							$error_code = \sprintf( 'Invalid%sFileName', $oo_type_ucfirst );

							// Don't duplicate "interface/trait/enum" in the filename.
							$expected_suffix = '-' . $oo_type;
							if ( \substr( $expected, -\strlen( $expected_suffix ) ) !== $expected_suffix ) {
								$expected .= $expected_suffix;
							}
							break;
					}
				}
			}
			else {
				$has_function = $phpcsFile->findNext( \T_FUNCTION, $stackPtr );
				if ( $has_function !== false && $file_name !== 'functions' ) {
					$error      = 'Files containing function declarations should have "-functions" as a suffix.';
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
				$error . ' Expected "%s", but found "%s".',
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
	private function is_file_excluded( File $phpcsFile, $path_to_file ) {
		$this->validate_excluded_files( $phpcsFile );
		if ( empty( $this->validated_excluded_files ) ) {
			return false;
		}

		$path_to_file = $this->normalize_directory_separators( $path_to_file );
		$path_to_file = \ltrim( $path_to_file, '/' );

		return isset( $this->validated_excluded_files[ $path_to_file ] );
	}

	/**
	 * Clean a custom array property received from a ruleset.
	 *
	 * Deals with incorrectly passed custom array properties.
	 * - Remove whitespace surrounding values.
	 * - Remove empty array entries.
	 *
	 * @param array<string> $property The current property value.
	 *
	 * @return array<string>
	 */
	private function clean_custom_array_property( $property ) {
		return \array_filter( \array_map( 'trim', $property ) );
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

	/**
	 * Validate and sort the OO prefixes passed from a custom ruleset.
	 *
	 * This will only need to be done once in a normal PHPCS run, though for
	 * tests the function may be called multiple times.
	 *
	 * @return void
	 */
	private function validate_oo_prefixes() {
		if ( $this->previous_oo_prefixes === $this->oo_prefixes ) {
			return;
		}

		// Set the cache *before* validation so as to not break the above compare.
		$this->previous_oo_prefixes = $this->oo_prefixes;

		$this->clean_oo_prefixes = $this->clean_custom_array_property( $this->oo_prefixes );

		if ( ! empty( $this->clean_oo_prefixes ) ) {
			// Use reverse natural sorting to get the longest of overlapping prefixes first.
			\rsort( $this->clean_oo_prefixes, ( \SORT_NATURAL | \SORT_FLAG_CASE ) );
		}
	}

	/**
	 * Validate the list of excluded files passed from a custom ruleset.
	 *
	 * This will only need to be done once in a normal PHPCS run, though for
	 * tests the function may be called multiple times.
	 *
	 * @param File $phpcsFile The file being scanned.
	 *
	 * @return void
	 */
	private function validate_excluded_files( $phpcsFile ) {
		// The basepath check needs to be done first as otherwise the previous/current comparison would be broken.
		if ( ! isset( $phpcsFile->config->basepath ) ) {
			$phpcsFile->addWarning(
				'For the exclude property to work with relative file path files, the --basepath needs to be set.',
				0,
				'MissingBasePath'
			);

			// Only relevant for the tests: make sure previously set validated paths are cleared out.
			$this->validated_excluded_files = [];

			// No use continuing as we can't turn relative paths into absolute paths.
			return;
		}

		if ( $this->previous_excluded_files === $this->excluded_files_strict_check ) {
			return;
		}

		// Set the cache *before* validation so as to not break the above compare.
		$this->previous_excluded_files = $this->excluded_files_strict_check;

		// Reset a potentially previous set validated value.
		$this->validated_excluded_files = [];

		$exclude = $this->clean_custom_array_property( $this->excluded_files_strict_check );
		if ( empty( $exclude ) ) {
			return;
		}

		$base_path = $this->normalize_directory_separators( $phpcsFile->config->basepath );
		$exclude   = \array_map( [ $this, 'normalize_directory_separators' ], $exclude );

		foreach ( $exclude as $relative ) {
			if ( \strpos( $relative, '..' ) !== false ) {
				// Ignore paths containing path walking.
				continue;
			}

			if ( \strpos( $relative, './' ) === 0 ) {
				$relative = \substr( $relative, 2 );
			}

			/*
			 * Note: no need to check if the file really exists. We'll be doing a literal absolute path comparison,
			 * so if the file doesn't exist, it will never match.
			 */
			$this->validated_excluded_files[] = $base_path . '/' . $relative;
		}

		if ( ! empty( $this->validated_excluded_files ) ) {
			$this->validated_excluded_files = \array_flip( $this->validated_excluded_files );
		}
	}
}
