<?php

namespace YoastCS\Yoast\Utils;

/**
 * Utility class containing methods for working with paths.
 *
 * ---------------------------------------------------------------------------------------------
 * This class is only intended for internal use by YoastCS and is not part of the public API.
 * This also means that it has no promise of backward compatibility. Use at your own risk.
 * ---------------------------------------------------------------------------------------------
 *
 * {@internal The functionality in this class will likely be replaced at some point in
 * the future by functions from PHPCSUtils.}
 *
 * @internal
 *
 * @since 3.0.0
 */
final class PathHelper {

	/**
	 * Normalize an absolute path to forward slashes and to include a trailing slash.
	 *
	 * @param string $path Absolute file or directory path.
	 *
	 * @return string
	 */
	public static function normalize_absolute_path( $path ) {
		return self::trailingslashit( self::normalize_directory_separators( $path ) );
	}

	/**
	 * Normalize a relative path to forward slashes and normalize the leading/trailing
	 * slashes (no leading, yes trailing).
	 *
	 * @param string $path Relative file or directory path.
	 *
	 * @return string
	 */
	public static function normalize_relative_path( $path ) {
		return self::remove_leading_slash( self::normalize_absolute_path( $path ) );
	}

	/**
	 * Normalize all directory separators to be a forward slash.
	 *
	 * @param string $path File or directory path.
	 *
	 * @return string
	 */
	public static function normalize_directory_separators( $path ) {
		return \strtr( (string) $path, '\\', '/' );
	}

	/**
	 * Ensure that a path does not start with a leading slash.
	 *
	 * @param string $path File or directory path.
	 *
	 * @return string
	 */
	public static function remove_leading_slash( $path ) {
		return \ltrim( (string) $path, '/\\' );
	}

	/**
	 * Ensure that a directory path ends on a trailing slash.
	 *
	 * Includes safeguard against adding a trailing slash to a path to a file.
	 *
	 * @param string $path File or directory path.
	 *
	 * @return string
	 */
	public static function trailingslashit( $path ) {
		if ( ! \is_string( $path ) || $path === '' ) {
			return '';
		}

		$extension = '';
		$last_char = \substr( $path, -1 );
		if ( $last_char !== '/' && $last_char !== '\\' ) {
			// This may be a file, check if it has a file extension.
			$extension = \pathinfo( $path, \PATHINFO_EXTENSION );
		}

		if ( $extension !== '' ) {
			return $path;
		}

		return \rtrim( (string) $path, '/\\' ) . '/';
	}

	/**
	 * Remove a partial path from the start of a path.
	 *
	 * Note: this function does not normalize paths prior to comparing them.
	 * If this is needed, normalization should be done prior to passing
	 * the `$haystack` and `$needle` parameters to this function.
	 *
	 * Also note that this function handles paths case-sensitively.
	 *
	 * @param string $path     The path of the file.
	 * @param string $basepath The base path to remove.
	 *
	 * @return string The remaining portion of $path after $basepath has been stripped
	 *                or the original $path value if the value did not start with $basepath
	 *                or if $basepath was an empty string.
	 */
	public static function strip_basepath( $path, $basepath ) {
		if ( empty( $basepath )
			|| self::path_starts_with( $path, $basepath ) === false
		) {
			return $path;
		}

		$path = \substr( $path, \strlen( $basepath ) );
		$path = self::remove_leading_slash( $path );

		if ( $path === '' ) {
			$path = '.';
		}

		return $path;
	}

	/**
	 * Check whether one file/directory path starts with another path.
	 *
	 * Recommended to be used when both paths are absolute.
	 *
	 * Note: this function does not normalize paths prior to comparing them.
	 * If this is needed, normalization should be done prior to passing
	 * the `$haystack` and `$needle` parameters to this function.
	 *
	 * Also note that this function does a case-sensitive comparison.
	 *
	 * @param string $haystack Directory path to search in.
	 * @param string $needle   Path the haystack path should start with.
	 *
	 * @return bool
	 */
	public static function path_starts_with( $haystack, $needle ) {
		return ( \strncmp( $haystack, $needle, \strlen( $needle ) ) === 0 );
	}
}
