<?php

namespace YoastCS\Yoast\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Common;
use PHP_CodeSniffer\Util\Tokens;
use YoastCS\Yoast\Utils\CustomPrefixesTrait;

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
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   2.0.0
 */
class NamespaceNameSniff implements Sniff {

	use CustomPrefixesTrait;

	/**
	 * Project root(s).
	 *
	 * When the _real_ project root as set in `$basepath` is not the
	 * starting point for the translation between directories and levels,
	 * one or more sub-directories of the project root can be indicated
	 * as starting points for the translation.
	 *
	 * @var string[]
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
	 * Validated src_directories will look like `src/`, i.e.:
	 * - have linux slashes;
	 * - not be prefixed with a slash;
	 * - have a trailing slash.
	 *
	 * @var string[]
	 */
	private $validated_src_directory = [];

	/**
	 * Cache of previously set project roots.
	 *
	 * Prevents having to do the same validation over and over again.
	 *
	 * @var string[]
	 */
	private $previous_src_directory = [];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return (int|string)[]
	 */
	public function register() {
		return [ \T_NAMESPACE ];
	}

	/**
	 * Filter out all prefixes which don't have namespace separators.
	 *
	 * @param string[] $prefixes The unvalidated prefixes.
	 *
	 * @return string[]
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

		$tokens = $phpcsFile->getTokens();

		if ( empty( $tokens[ $stackPtr ]['conditions'] ) === false ) {
			// Not a namespace declaration.
			return;
		}

		$next_non_empty = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
		if ( $tokens[ $next_non_empty ]['code'] === \T_NS_SEPARATOR ) {
			// Not a namespace declaration.
			return;
		}

		// Get the complete namespace name.
		$namespace_name = $tokens[ $next_non_empty ]['content'];
		for ( $i = ( $next_non_empty + 1 ); $i < $phpcsFile->numTokens; $i++ ) {
			if ( isset( Tokens::$emptyTokens[ $tokens[ $i ]['code'] ] ) ) {
				continue;
			}

			if ( $tokens[ $i ]['code'] !== \T_STRING && $tokens[ $i ]['code'] !== \T_NS_SEPARATOR ) {
				// Reached end of the namespace declaration.
				break;
			}

			$namespace_name .= $tokens[ $i ]['content'];
		}

		if ( $i === $phpcsFile->numTokens ) {
			// Live coding.
			return;
		}

		$this->validate_prefixes();

		// Strip off the plugin prefix.
		$namespace_name_no_prefix = $namespace_name;
		$found_prefix             = '';
		if ( ! empty( $this->validated_prefixes ) ) {
			$name = $namespace_name . '\\'; // Validated prefixes always have a \ at the end.
			foreach ( $this->validated_prefixes as $prefix ) {
				if ( \strpos( $name . '\\', $prefix ) === 0 ) {
					$namespace_name_no_prefix = \rtrim( \substr( $name, \strlen( $prefix ) ), '\\' );
					$found_prefix             = \rtrim( $prefix, '\\' );
					break;
				}
			}
			unset( $prefix, $name );
		}

		/*
		 * Check the namespace level depth.
		 */
		if ( $namespace_name_no_prefix !== '' ) {
			$namespace_for_level_check = $namespace_name_no_prefix;

			// Allow for `Tests\` and `Tests\Doubles\` after the prefix.
			$starts_with_tests = ( \strpos( $namespace_for_level_check, 'Tests\\' ) === 0 );
			if ( $starts_with_tests === true ) {
				$namespace_for_level_check = \substr( $namespace_for_level_check, 6 );
			}

			if ( ( $starts_with_tests === true
				// Allow for non-conventional test directory layout, like in YoastSEO Free.
				|| \strpos( $found_prefix, '\\Tests\\' ) !== false )
				&& \strpos( $namespace_for_level_check, 'Doubles\\' ) === 0
			) {
				$namespace_for_level_check = \substr( $namespace_for_level_check, 8 );
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

		$base_path = $this->normalize_directory_separators( $phpcsFile->config->basepath );

		// Stripping potential quotes to ensure `stdin_path` passed by IDEs does not include quotes.
		$file = \preg_replace( '`^([\'"])(.*)\1$`Ds', '$2', $phpcsFile->getFileName() );

		if ( $file === 'STDIN' ) {
			return;
		}

		$directory          = $this->normalize_directory_separators( \dirname( $file ) );
		$relative_directory = Common::stripBasepath( $directory, $base_path );
		if ( $relative_directory === '.' ) {
			$relative_directory = '';
		}
		else {
			if ( $relative_directory[0] !== '/' ) {
				/*
				 * Basepath stripping appears to work differently depending on OS.
				 * On Windows there still is a slash at the start, on Unix/Mac there isn't.
				 * Normalize to allow comparison.
				 */
				$relative_directory = '/' . $relative_directory;
			}

			// Add trailing slash to prevent matching '/sub' to '/sub-directory'.
			$relative_directory .= '/';
		}

		$this->validate_src_directory();

		if ( empty( $this->validated_src_directory ) === false ) {
			foreach ( $this->validated_src_directory as $subdirectory ) {
				if ( \strpos( $relative_directory, $subdirectory ) !== 0 ) {
					continue;
				}

				$relative_directory = \substr( $relative_directory, \strlen( $subdirectory ) );
				break;
			}
		}

		// Now any potential src directory has been stripped, remove the slashes again.
		$relative_directory = \trim( $relative_directory, '/' );

		$namespace_name_for_translation = \str_replace(
			[ '_', '\\' ], // Find.
			[ '-', '/' ],  // Replace with.
			$namespace_name_no_prefix
		);

		if ( \strcasecmp( $relative_directory, $namespace_name_for_translation ) === 0 ) {
			return;
		}

		$expected = '[Plugin\Prefix]';
		if ( $found_prefix !== '' ) {
			$expected = $found_prefix;
		}

		if ( $relative_directory !== '' ) {
			$levels = \explode( '/', $relative_directory );
			$levels = \array_filter( $levels ); // Remove empties.
			foreach ( $levels as $level ) {
				$words     = \explode( '-', $level );
				$words     = \array_map( 'ucfirst', $words );
				$expected .= '\\' . \implode( '_', $words );
			}
		}

		$phpcsFile->addError(
			'The namespace (sub)level(s) should reflect the directory path to the file. Expected: "%s"; Found: "%s"',
			$stackPtr,
			'Invalid',
			[
				$expected,
				$namespace_name,
			]
		);
	}

	/**
	 * Validate a $src_directory property when set in a custom ruleset.
	 *
	 * @return void
	 */
	protected function validate_src_directory() {
		if ( $this->previous_src_directory === $this->src_directory ) {
			return;
		}

		// Set the cache *before* validation so as to not break the above compare.
		$this->previous_src_directory = $this->src_directory;

		$src_directory = (array) $this->src_directory;
		$src_directory = \array_filter( \array_map( 'trim', $src_directory ) );

		if ( empty( $src_directory ) ) {
			$this->validated_src_directory = [];
			return;
		}

		$validated = [];
		foreach ( $src_directory as $directory ) {
			if ( \strpos( $directory, '..' ) !== false ) {
				// Do not allow walking up the directory hierarchy.
				continue;
			}

			$directory = $this->normalize_directory_separators( $directory );

			if ( $directory === '.' ) {
				// The basepath/root directory is the default, so ignore.
				continue;
			}

			if ( \strpos( $directory, './' ) === 0 ) {
				$directory = \substr( $directory, 2 );
			}

			if ( $directory === '' ) {
				continue;
			}

			$validated[] = '/' . $directory . '/';
		}

		// Use reverse natural sorting to get the longest directory first.
		\rsort( $validated, ( \SORT_NATURAL | \SORT_FLAG_CASE ) );

		// Set the validated prefixes cache.
		$this->validated_src_directory = $validated;
	}

	/**
	 * Normalize all directory separators to be a forward slash and remove prefixed and suffixed slashes.
	 *
	 * @param string $path Path to normalize.
	 *
	 * @return string
	 */
	private function normalize_directory_separators( $path ) {
		return \trim( \strtr( $path, '\\', '/' ), '/' );
	}
}
