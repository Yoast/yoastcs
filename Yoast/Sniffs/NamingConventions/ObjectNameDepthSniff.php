<?php

namespace YoastCS\Yoast\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\ObjectDeclarations;
use WordPressCS\WordPress\Helpers\SnakeCaseHelper;

/**
 * Check the number of words in object names declared within a namespace.
 *
 * @since 2.0.0
 * @since 3.0.0 This sniff no longer extends the WPCS abstract Sniff class.
 */
final class ObjectNameDepthSniff implements Sniff {

	/**
	 * Suffixes commonly used for classes in the test suites.
	 *
	 * The key is the suffix in lowercase. The value indicates whether this suffix is
	 * only allowed when the class extends a known test class.
	 * The value is currently unused.
	 *
	 * @var array<string, bool>
	 */
	private const TEST_SUFFIXES = [
		'test'     => true,
		'testcase' => true,
		'mock'     => false,
		'double'   => false,
	];

	/**
	 * Maximum number of words.
	 *
	 * The maximum number of words an object name should consist of, each
	 * separated by an underscore.
	 *
	 * If the name consists of more words, an ERROR will be thrown.
	 *
	 * @var int
	 */
	public $max_words = 3;

	/**
	 * Recommended maximum number of words.
	 *
	 * The recommended maximum number of words an object name should consist of, each
	 * separated by an underscore.
	 *
	 * If the name consists of more words, a WARNING will be thrown.
	 *
	 * @var int
	 */
	public $recommended_max_words = 3;

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
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		// Check whether we are in a namespace or not.
		if ( Namespaces::determineNamespace( $phpcsFile, $stackPtr ) === '' ) {
			return;
		}

		$object_name = ObjectDeclarations::getName( $phpcsFile, $stackPtr );
		if ( empty( $object_name ) ) {
			return;
		}

		$snakecase_object_name = \ltrim( $object_name, '_' );

		// Handle names which are potentially in CamelCaps.
		if ( \strpos( $snakecase_object_name, '_' ) === false ) {
			$snakecase_object_name = SnakeCaseHelper::get_suggestion( $snakecase_object_name );

			// Always treat "TestCase" as one word.
			if ( \substr( $snakecase_object_name, -9 ) === 'test_case' ) {
				$snakecase_object_name = \substr( $snakecase_object_name, 0, -9 ) . 'testcase';
			}
		}

		$parts      = \explode( '_', $snakecase_object_name );
		$part_count = \count( $parts );

		/*
		 * Allow the OO name to be one part longer for confirmed test/mock/double OO structures.
		 */
		$tokens = $phpcsFile->getTokens();

		$last = \strtolower( \array_pop( $parts ) );
		if ( isset( self::TEST_SUFFIXES[ $last ] ) ) {
			if ( $tokens[ $stackPtr ]['code'] === \T_ENUM ) {
				// Enums cannot extend, but a mock/double without direct link to the parent could be needed.
				--$part_count;
			}
			else {
				$extends = ObjectDeclarations::findExtendedClassName( $phpcsFile, $stackPtr );
				if ( \is_string( $extends ) ) {
					--$part_count;
				}
			}
		}

		if ( $part_count <= $this->recommended_max_words && $part_count <= $this->max_words ) {
			$phpcsFile->recordMetric( $stackPtr, 'Nr of words in object name', $part_count );
			return;
		}

		// Check if the OO structure is deprecated.
		$ignore                  = Collections::classModifierKeywords();
		$ignore[ \T_WHITESPACE ] = \T_WHITESPACE;

		$comment_end = $stackPtr;
		for ( $comment_end = ( $stackPtr - 1 ); $comment_end >= 0; $comment_end-- ) {
			if ( isset( $ignore[ $tokens[ $comment_end ]['code'] ] ) === true ) {
				continue;
			}

			if ( $tokens[ $comment_end ]['code'] === \T_ATTRIBUTE_END
				&& isset( $tokens[ $comment_end ]['attribute_opener'] ) === true
			) {
				$comment_end = $tokens[ $comment_end ]['attribute_opener'];
				continue;
			}

			break;
		}

		if ( $tokens[ $comment_end ]['code'] === \T_DOC_COMMENT_CLOSE_TAG ) {
			// Only check if the OO structure has a docblock.
			$comment_start = $tokens[ $comment_end ]['comment_opener'];
			foreach ( $tokens[ $comment_start ]['comment_tags'] as $tag ) {
				if ( $tokens[ $tag ]['content'] === '@deprecated' ) {
					// Deprecated OO structure, ignore.
					return;
				}
			}
		}

		$phpcsFile->recordMetric( $stackPtr, 'Nr of words in object name', $part_count );

		// Not a deprecated OO structure, this OO structure should comply with the rules.
		$object_type = 'a ' . $tokens[ $stackPtr ]['content'];
		if ( $tokens[ $stackPtr ]['code'] === \T_INTERFACE || $tokens[ $stackPtr ]['code'] === \T_ENUM ) {
			$object_type = 'an ' . $tokens[ $stackPtr ]['content'];
		}

		if ( $part_count > $this->max_words ) {
			$error = 'The name of %s is not allowed to consist of more than %d words. Words found: %d in %s';
			$data  = [
				$object_type,
				$this->max_words,
				$part_count,
				$object_name,
			];

			$phpcsFile->addError( $error, $stackPtr, 'MaxExceeded', $data );
		}
		elseif ( $part_count > $this->recommended_max_words ) {
			$error = 'The name of %s should not consist of more than %d words. Words found: %d in %s';
			$data  = [
				$object_type,
				$this->recommended_max_words,
				$part_count,
				$object_name,
			];

			$phpcsFile->addWarning( $error, $stackPtr, 'TooLong', $data );
		}
	}
}
