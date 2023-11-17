<?php

namespace YoastCS\Yoast\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\NamingConventions;
use PHPCSUtils\Utils\TextStrings;
use YoastCS\Yoast\Utils\CustomPrefixesTrait;
use YoastCS\Yoast\Utils\PathHelper;
use YoastCS\Yoast\Utils\PathValidationHelper;
use YoastCS\Yoast\Utils\PSR4PathsTrait;

/**
 * Check namespace name declarations.
 *
 * Namespace names should consist of:
 * - A prefix (checked via the WPCS PrefixAllGlobals sniff);
 * - Zero or more \Sub\Levels.
 *
 * This sniff checks that the levels do not exceed the recommended and maximum depth
 * as well as that the levels directly translate to the (sub-)directory a file is
 * placed in.
 *
 * @since 2.0.0
 * @since 3.0.0 - Added new check to verify a prefix is used.
 *              - The sniff now also has the ability to check for PSR-4 compliant namespace names.
 *
 * @uses \YoastCS\Yoast\Utils\CustomPrefixesTrait::$prefixes
 * @uses \YoastCS\Yoast\Utils\PSR4PathsTrait::$psr4_paths
 */
final class NamespaceNameSniff implements Sniff {

	use CustomPrefixesTrait;
	use PSR4PathsTrait;

	/**
	 * Double/Mock/Fixture directories to allow for.
	 *
	 * @var array<string, int> Key is the subdirectory name, value the length of that name.
	 */
	private const DOUBLE_DIRS = [
		'\Doubles\\'  => 9,
		'\Mocks\\'    => 7,
		'\Fixtures\\' => 10,
	];

	/**
	 * Project root(s).
	 *
	 * When the _real_ project root as set in `$basepath` is not the
	 * starting point for the translation between directories and levels,
	 * one or more sub-directories of the project root can be indicated
	 * as starting points for the translation.
	 *
	 * @var array<string>
	 */
	public $src_directory = [];

	/**
	 * Maximum number of levels.
	 *
	 * The maximum number of sub-levels a namespace name should consist of, each
	 * separated by a namespace separator.
	 *
	 * If the name consists of more sub-levels, an ERROR will be thrown.
	 *
	 * @var int
	 */
	public $max_levels = 3;

	/**
	 * Recommended maximum number of levels.
	 *
	 * The recommended maximum number of sub-levels a namespace name should consist of, each
	 * separated by a namespace separator.
	 *
	 * If the name consists of more sub-levels, a WARNING will be thrown.
	 *
	 * @var int
	 */
	public $recommended_max_levels = 2;

	/**
	 * Project roots after validation.
	 *
	 * Validated src_directories will look like "$basepath/src/", i.e.:
	 * - absolute paths;
	 * - with linux slashes;
	 * - and a trailing slash.
	 *
	 * @var array<string>
	 */
	private $validated_src_directory;

	/**
	 * Cache of previously set project roots.
	 *
	 * Prevents having to do the same validation over and over again.
	 *
	 * @var array<string>
	 */
	private $previous_src_directory;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		return [ \T_NAMESPACE ];
	}

	/**
	 * Filter out all prefixes which don't have namespace separators.
	 *
	 * @param array<string> $prefixes The unvalidated prefixes.
	 *
	 * @return array<string>
	 */
	protected function filter_prefixes( $prefixes ) {
		return $this->filter_allow_only_namespace_prefixes( $prefixes );
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$namespace_name = Namespaces::getDeclaredName( $phpcsFile, $stackPtr );
		if ( empty( $namespace_name ) ) {
			// Either not a namespace declaration or global namespace.
			return;
		}

		// Stripping potential quotes to ensure `stdin_path` passed by IDEs does not include quotes.
		$file = TextStrings::stripQuotes( $phpcsFile->getFileName() );
		if ( $file === 'STDIN' ) {
			$file = ''; // @codeCoverageIgnore
		}
		else {
			$file = PathHelper::normalize_absolute_path( $file );
		}

		$valid_prefixes = [];
		$psr4_info      = false;
		if ( $file !== '' ) {
			$psr4_info = $this->get_psr4_info( $phpcsFile, $file );
		}

		if ( \is_array( $psr4_info ) ) {
			// If a PSR4 path matched, there will only ever be one valid prefix for the matched path.
			$valid_prefixes = [ $psr4_info['prefix'] . '\\' ];
		}
		else {
			// Safeguard that the PSR-4 prefixes are always included.
			// Makes sure level depth check still happens even if there is no basepath or path doesn't match PSR-4 path.
			if ( empty( $this->prefixes ) && ! empty( $this->psr4_paths ) ) {
				$this->prefixes = \array_keys( $this->psr4_paths );
			}

			$this->validate_prefixes();
			$valid_prefixes = $this->validated_prefixes;
		}

		// Strip off the (longest) plugin prefix.
		$namespace_name_no_prefix = $namespace_name;
		$found_prefix             = '';
		if ( ! empty( $valid_prefixes ) ) {
			$name = $namespace_name . '\\'; // Validated prefixes always have a \ at the end.
			foreach ( $valid_prefixes as $prefix ) {
				if ( \strpos( $name, $prefix ) === 0 ) {
					$namespace_name_no_prefix = \rtrim( \substr( $name, \strlen( $prefix ) ), '\\' );
					$found_prefix             = \rtrim( $prefix, '\\' );
					break;
				}
			}
			unset( $prefix, $name );
		}

		// Check if a prefix is used.
		if ( ! empty( $valid_prefixes ) && $found_prefix === '' ) {
			$prefixes = $valid_prefixes;

			if ( $psr4_info !== false ) {
				$error     = 'PSR-4 namespace name for this path is required to start with the "%1$s" prefix.';
				$errorcode = 'MissingPSR4Prefix';
			}
			else {
				$error     = 'A namespace name is required to start with one of the following prefixes: "%s"';
				$errorcode = 'MissingPrefix';

				$prefixes = \array_merge( $prefixes, \array_keys( $this->psr4_paths ) );
				$prefixes = \array_unique( $prefixes );

				if ( \count( $prefixes ) === 1 ) {
					$error = 'A namespace name is required to start with the "%s" prefix.';
				}
				else {
					\natcasesort( $prefixes );
				}
			}

			$data = [ \implode( '", "', $prefixes ) ];

			$phpcsFile->addError( $error, $stackPtr, $errorcode, $data );
		}

		/*
		 * Check the namespace level depth.
		 */
		if ( $namespace_name_no_prefix !== '' ) {
			$namespace_for_level_check = $namespace_name_no_prefix;

			// Allow for a variation of `Tests\` and `Tests\*\Doubles\` after the prefix.
			$starts_with_tests      = ( \strpos( $namespace_for_level_check, 'Tests\\' ) === 0 );
			$prefix_ends_with_tests = ( \substr( $found_prefix, -6 ) === '\Tests' );
			if ( $starts_with_tests === true || $prefix_ends_with_tests === true ) {
				$stripped = false;
				foreach ( self::DOUBLE_DIRS as $dir => $length ) {
					if ( \strpos( $namespace_for_level_check, $dir ) !== false ) {
						$namespace_for_level_check = \substr( $namespace_for_level_check, ( \strpos( $namespace_for_level_check, $dir ) + $length ) );
						$stripped                  = true;
						break;
					}
				}

				if ( $stripped === false ) {
					if ( $starts_with_tests === true ) {
						// No double dir found, now check/strip typical test dirs.
						if ( \strpos( $namespace_for_level_check, 'Tests\WP\\' ) === 0 ) {
							$namespace_for_level_check = \substr( $namespace_for_level_check, 9 );
						}
						elseif ( \strpos( $namespace_for_level_check, 'Tests\Unit\\' ) === 0 ) {
							$namespace_for_level_check = \substr( $namespace_for_level_check, 11 );
						}
						else {
							// Okay, so this only has the `Tests` prefix, just strip it.
							$namespace_for_level_check = \substr( $namespace_for_level_check, 6 );
						}
					}
					elseif ( $prefix_ends_with_tests === true ) {
						// Prefix which already includes `Tests`.
						if ( \strpos( $namespace_for_level_check, 'WP\\' ) === 0 ) {
							$namespace_for_level_check = \substr( $namespace_for_level_check, 3 );
						}
						elseif ( \strpos( $namespace_for_level_check, 'Unit\\' ) === 0 ) {
							$namespace_for_level_check = \substr( $namespace_for_level_check, 5 );
						}
					}
				}
			}

			$parts      = \explode( '\\', $namespace_for_level_check );
			$part_count = \count( $parts );

			$phpcsFile->recordMetric( $stackPtr, 'Nr of levels in namespace name', $part_count );

			if ( $part_count > $this->max_levels ) {
				$error = 'A namespace name is not allowed to be more than %d levels deep (excluding the prefix). Level depth found: %d in %s';
				$data  = [
					$this->max_levels,
					$part_count,
					$namespace_name,
				];

				$phpcsFile->addError( $error, $stackPtr, 'MaxExceeded', $data );
			}
			elseif ( $part_count > $this->recommended_max_levels ) {
				$error = 'A namespace name should be no more than %d levels deep (excluding the prefix). Level depth found: %d in %s';
				$data  = [
					$this->recommended_max_levels,
					$part_count,
					$namespace_name,
				];

				$phpcsFile->addWarning( $error, $stackPtr, 'TooLong', $data );
			}
		}

		/*
		 * Prepare to check the path to level translation.
		 */

		if ( ! isset( $phpcsFile->config->basepath ) ) {
			// If no basepath is set, we don't know the project root, so bow out.
			return;
		}

		if ( $file === '' ) {
			// STDIN.
			return; // @codeCoverageIgnore
		}

		$relative_directory = '';
		if ( \is_array( $psr4_info ) ) {
			$relative_directory = $psr4_info['relative'];
		}
		else {
			$directory = PathHelper::normalize_absolute_path( \dirname( $file ) );

			$this->validate_src_directory( $phpcsFile );

			if ( empty( $this->validated_src_directory ) === false ) {
				foreach ( $this->validated_src_directory as $absolute_src_path ) {
					if ( PathHelper::path_starts_with( $directory, $absolute_src_path ) === false ) {
						continue;
					}

					$relative_directory = PathHelper::strip_basepath( $directory, $absolute_src_path );
					break;
				}
			}
		}

		if ( $relative_directory === '.' ) {
			$relative_directory = '';
		}

		// Now any potential src directory has been stripped, remove surrounding slashes.
		$relative_directory = \trim( $relative_directory, '/' );

		// Directory to namespace translation.
		$expected = '[Plugin\Prefix]';
		if ( $found_prefix !== '' ) {
			$expected = $found_prefix;
		}
		// Namespace name doesn't have the correct prefix, but we do know what the prefix should be.
		elseif ( \is_array( $psr4_info ) ) {
			$expected = $psr4_info['prefix'];
		}
		elseif ( \count( $valid_prefixes ) === 1 ) {
			$expected = \rtrim( $valid_prefixes[0], '\\' );
		}

		$clean            = [];
		$name_for_compare = '';

		if ( $relative_directory !== '' ) {
			$levels = \explode( '/', $relative_directory );
			$levels = \array_filter( $levels ); // Remove empties, just in case.

			foreach ( $levels as $level ) {
				$cleaned_level = $level;
				if ( $psr4_info === false ) {
					$cleaned_level = \preg_replace( '`[[:punct:]]`', '_', $cleaned_level );
					$words         = \explode( '_', $cleaned_level );
					$words         = \array_map( 'ucfirst', $words );
					$cleaned_level = \implode( '_', $words );
				}

				if ( NamingConventions::isValidIdentifierName( $cleaned_level ) === false ) {
					$phpcsFile->addError(
						'Translating the directory name to a namespace name would not yield a valid namespace name. Rename the "%s" directory.',
						0,
						'DirectoryInvalid',
						[ $level ]
					);

					// Continuing would be useless as the name would be invalid anyway.
					return;
				}

				$clean[] = $cleaned_level;
			}

			$name_for_compare = \implode( '\\', $clean );
		}

		// Check whether the namespace name complies with the rules.
		if ( $psr4_info !== false ) {
			// Check for PSR-4 compliant namespace.
			if ( \strcmp( $name_for_compare, $namespace_name_no_prefix ) === 0 ) {
				return;
			}

			$error = 'Directory marked as a PSR-4 path. The namespace name should match the path exactly in a case-sensitive manner. Expected namespace name: "%s"; found: "%s"';
			$code  = 'NotPSR4Compliant';
		}
		else {
			// Check for "old-style" namespace.
			if ( \strcasecmp( $name_for_compare, $namespace_name_no_prefix ) === 0 ) {
				return;
			}

			$error = 'The namespace (sub)level(s) should reflect the directory path to the file. Expected: "%s"; Found: "%s"';
			$code  = 'Invalid';
		}

		if ( $name_for_compare !== '' ) {
			$expected .= '\\' . $name_for_compare;
		}

		$data = [
			$expected,
			$namespace_name,
		];

		$phpcsFile->addError( $error, $stackPtr, $code, $data );
	}

	/**
	 * Validate a $src_directory property when set in a custom ruleset.
	 *
	 * @param File $phpcsFile The file being scanned.
	 *
	 * @return void
	 */
	private function validate_src_directory( File $phpcsFile ) {
		if ( $this->previous_src_directory === $this->src_directory ) {
			return;
		}

		// Set the cache *before* validation so as to not break the above compare.
		$this->previous_src_directory = $this->src_directory;

		// Clear out previously validated src directories.
		$this->validated_src_directory = [];

		// Note: the check whether a basepath is available is done in the main `process()` routine.
		$base_path = PathHelper::normalize_absolute_path( $phpcsFile->config->basepath );

		// Add any src directories.
		$absolute_paths = PathValidationHelper::relative_to_absolute( $phpcsFile, $this->src_directory );

		// The base path is always a valid src directory.
		if ( isset( $absolute_paths['.'] ) === false ) {
			$absolute_paths['.'] = $base_path;
		}

		$this->validated_src_directory = \array_unique( $absolute_paths );

		// Use reverse natural sorting to get the longest directory first.
		\rsort( $this->validated_src_directory, ( \SORT_NATURAL | \SORT_FLAG_CASE ) );
	}
}
