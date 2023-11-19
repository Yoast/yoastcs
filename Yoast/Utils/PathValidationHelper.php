<?php

namespace YoastCS\Yoast\Utils;

use PHP_CodeSniffer\Files\File;

/**
 * Utility class containing methods for validating paths.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is only intended for internal use by YoastCS and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * @internal
 *
 * @since 3.0.0
 */
final class PathValidationHelper {

	/**
	 * Convert an array with relative paths to an array with absolute paths.
	 *
	 * Note: path walking is prohibited and relative paths containing ".." will be ignored.
	 *
	 * @param File          $phpcsFile      The current file being scanned.
	 * @param array<string> $relative_paths Array of relative paths which should become absolute paths.
	 *                                      Paths are expected to be relative to the "basepath" setting.
	 *
	 * @return array<string, string> Array of absolute paths or an empty array if the conversion could not be executed.
	 *                               The array will contain the original relative paths as the keys and the absolute paths
	 *                               as the values.
	 *                               Note: multiple relative paths may result in the same absolute path.
	 *                               The values are not guaranteed to be unique!
	 */
	public static function relative_to_absolute( File $phpcsFile, array $relative_paths ) {
		$absolute = [];

		if ( ! isset( $phpcsFile->config->basepath ) ) {
			// No use continuing as we can't turn relative paths into absolute paths.
			return $absolute;
		}

		$base_path = PathHelper::normalize_path( $phpcsFile->config->basepath );

		foreach ( $relative_paths as $path ) {
			$result_path = \trim( $path );
			$result_path = PathHelper::normalize_path( $result_path );

			if ( $result_path === '' ) {
				continue;
			}

			if ( \strpos( $result_path, '..' ) !== false ) {
				// Ignore paths containing path walking.
				continue;
			}

			if ( $result_path === './' ) {
				$absolute[ $path ] = $base_path;
				continue;
			}

			if ( \strpos( $result_path, './' ) === 0 ) {
				$result_path = \substr( $result_path, 2 );
			}

			$absolute[ $path ] = $base_path . $result_path;
		}

		return $absolute;
	}
}
