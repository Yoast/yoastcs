<?php

namespace YoastCS\Yoast\Utils;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use PHP_CodeSniffer\Files\File;
use PHPCSUtils\Utils\TextStrings;

/**
 * Trait to add a custom `$psr4_paths` property to sniffs. Including associated utility functions.
 *
 * @since 3.0.0
 */
trait PSR4PathsTrait {

	/**
	 * PSR-4 prefix-path definitions as supported by Composer.
	 *
	 * Example of how to set this:
	 * ```xml
	 * <rule ref="Yoast.Cat.SniffName">
	 *   <properties>
	 *     <property name="psr4_paths" type="array">
	 *       <!-- Prefix mapped to single directory. -->
	 *       <element key="Monolog\\" value="src/"/>
	 *       <!-- Prefix mapped to multiple directories. -->
	 *       <element key="Vendor\\Namespace\\" value="src/,lib/"/>
	 *     </property>
	 *   </properties>
	 * </rule>
	 * ```
	 *
	 * Note: paths are handled case-sensitively!
	 *
	 * @var array<string, string> Key should be the prefix, value a comma-separated list of relative paths.
	 */
	public $psr4_paths = [];

	/**
	 * Cache of previously set list of psr4 paths.
	 *
	 * Prevents having to do the same path validation over and over again.
	 *
	 * @var array<string, string>
	 */
	private $previous_psr4_paths = [];

	/**
	 * Validated & cleaned up list of absolute paths to the directories expecting PSR-4 file names
	 * with their associated prefixes.
	 *
	 * @var array<string, string> Key is the absolute path, value the applicable prefix without trailing slash.
	 */
	private $validated_psr4_paths = [];

	/**
	 * Check if the file is in one of the PSR4 directories.
	 *
	 * @param File   $phpcsFile    The file being scanned.
	 * @param string $path_to_file Optional The absolute path to the file currently being examined.
	 *                             If not provided, the file name will be retrieved from the File object.
	 *
	 * @return bool
	 */
	final protected function is_in_psr4_path( File $phpcsFile, $path_to_file = '' ) {
		return \is_array( $this->get_psr4_info( $phpcsFile, $path_to_file ) );
	}

	/**
	 * Retrieve all applicable information for a PSR-4 path.
	 *
	 * @param File   $phpcsFile    The file being scanned.
	 * @param string $path_to_file Optional The absolute path to the file currently being examined.
	 *                             If not provided, the file name will be retrieved from the File object.
	 *
	 * @return array<string, string>|false Array with information about the PSR-4 path. Otherwise FALSE.
	 */
	final protected function get_psr4_info( File $phpcsFile, $path_to_file = '' ) {
		if ( $path_to_file === '' ) {
			$path_to_file = TextStrings::stripQuotes( $phpcsFile->getFileName() );
			if ( $path_to_file === 'STDIN' ) {
				return false;
			}
		}

		$this->validate_psr4_paths( $phpcsFile );
		if ( empty( $this->validated_psr4_paths ) ) {
			return false;
		}

		$path_to_file = PathHelper::normalize_absolute_path( $path_to_file );

		foreach ( $this->validated_psr4_paths as $psr4_path => $prefix ) {
			$remainder = PathHelper::strip_basepath( $path_to_file, $psr4_path );
			if ( $remainder === $path_to_file ) {
				// Nothing was stripped, so this wasn't a match.
				continue;
			}

			return [
				'prefix'   => $prefix,
				'basepath' => $psr4_path,
				'relative' => \dirname( $remainder ),
			];
		}

		return false;
	}

	/**
	 * Validate the list of PSR-4 paths passed from a custom ruleset.
	 *
	 * This will only need to be done once in a normal PHPCS run, though for
	 * tests the function may be called multiple times.
	 *
	 * @param File $phpcsFile The file being scanned.
	 *
	 * @return void
	 *
	 * @throws RuntimeException When the `psr4_paths` array is missing keys.
	 * @throws RuntimeException When the `psr4_paths` array contains duplicate paths in multiple entries.
	 */
	private function validate_psr4_paths( File $phpcsFile ) {
		// The basepath check needs to be done first as otherwise the previous/current comparison would be broken.
		if ( ! isset( $phpcsFile->config->basepath ) ) {
			// Only relevant for the tests: make sure previously set validated paths are cleared out.
			$this->validated_psr4_paths = [];

			// No use continuing as we can't turn relative paths into absolute paths.
			return;
		}

		if ( $this->previous_psr4_paths === $this->psr4_paths ) {
			return;
		}

		// Set the cache *before* validation so as to not break the above compare.
		$this->previous_psr4_paths = $this->psr4_paths;

		$validated_paths = [];

		foreach ( $this->psr4_paths as $prefix => $paths ) {
			// @phpstan-ignore function.alreadyNarrowedType, identical.alwaysFalse (Defensive coding as the property value is user provided via the ruleset.)
			if ( \is_string( $prefix ) === false || $prefix === '' ) {
				throw new RuntimeException(
					'Invalid value passed for `psr4_paths`. Path "' . $paths . '" is not associated with a namespace prefix'
				);
			}

			$prefix = \rtrim( $prefix, '\\' );

			$paths = \rtrim( \ltrim( $paths, '[' ), ']' ); // Trim off potential [] copied over from Composer.json.
			$paths = \explode( ',', $paths );
			$paths = \array_map( 'trim', $paths );
			$paths = \array_map( [ TextStrings::class, 'stripQuotes' ], $paths ); // Trim off potential quotes around the paths copied over.
			$paths = \array_map( 'trim', $paths );
			$paths = PathValidationHelper::relative_to_absolute( $phpcsFile, $paths );
			$paths = \array_unique( $paths ); // Filter out multiple of the same paths for the same prefix.

			foreach ( $paths as $path ) {
				if ( isset( $validated_paths[ $path ] ) ) {
					throw new RuntimeException(
						'Invalid value passed for `psr4_paths`. Multiple prefixes include the same path. Problem path: ' . $path
					);
				}

				$validated_paths[ $path ] = $prefix;
			}
		}

		// Set the validated value.
		$this->validated_psr4_paths = $validated_paths;
	}
}
