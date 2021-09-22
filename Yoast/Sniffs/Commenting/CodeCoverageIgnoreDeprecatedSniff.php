<?php

namespace YoastCS\Yoast\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Verifies functions which are marked as `deprecated` have a `codeCoverageIgnore` tag
 * in their docblock.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   1.1.0
 */
class CodeCoverageIgnoreDeprecatedSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return (int|string)[]
	 */
	public function register() {
		return [
			\T_FUNCTION,
		];
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

		$ignore                  = Tokens::$methodPrefixes;
		$ignore[ \T_WHITESPACE ] = \T_WHITESPACE;

		for ( $commentEnd = ( $stackPtr - 1 ); $commentEnd >= 0; $commentEnd-- ) {
			if ( isset( $ignore[ $tokens[ $commentEnd ]['code'] ] ) === true ) {
				continue;
			}

			if ( $tokens[ $commentEnd ]['code'] === \T_ATTRIBUTE_END
				&& isset( $tokens[ $commentEnd ]['attribute_opener'] ) === true
			) {
				$commentEnd = $tokens[ $commentEnd ]['attribute_opener'];
				continue;
			}

			break;
		}


		if ( $tokens[ $commentEnd ]['code'] !== \T_DOC_COMMENT_CLOSE_TAG ) {
			// Function without (proper) docblock. Not our concern.
			return;
		}

		$commentStart = $tokens[ $commentEnd ]['comment_opener'];

		$deprecated = false;
		foreach ( $tokens[ $commentStart ]['comment_tags'] as $tag ) {
			if ( $tokens[ $tag ]['content'] === '@deprecated' ) {
				$deprecated = true;
				break;
			}
		}

		if ( $deprecated === false ) {
			// Not a deprecated function.
			return;
		}

		$codeCoverageIgnore = false;
		foreach ( $tokens[ $commentStart ]['comment_tags'] as $tag ) {
			if ( $tokens[ $tag ]['content'] === '@codeCoverageIgnore' ) {
				$codeCoverageIgnore = true;
				break;
			}
		}

		if ( $codeCoverageIgnore === true ) {
			// Docblock contains the @codeCoverageIgnore tag.
			return;
		}

		$hasTagAsString = $phpcsFile->findNext( \T_DOC_COMMENT_STRING, ( $commentStart + 1 ), $commentEnd, false, 'codeCoverageIgnore' );
		if ( $hasTagAsString !== false ) {
			$prev = $phpcsFile->findPrevious( \T_DOC_COMMENT_WHITESPACE, ( $hasTagAsString - 1 ), $commentStart, true );
			if ( $prev !== false && $tokens[ $prev ]['code'] === \T_DOC_COMMENT_STAR ) {
				$fix = $phpcsFile->addFixableError(
					'The `codeCoverageIgnore` annotation in the function docblock needs to be prefixed with an `@`.',
					$hasTagAsString,
					'NotTag'
				);
				if ( $fix === true ) {
					$phpcsFile->fixer->addContentBefore( $hasTagAsString, '@' );
				}

				return;
			}
		}

		// If we're still here, the tag is missing.
		$phpcsFile->addError(
			'The function is marked as deprecated, but the docblock does not contain a `@codeCoverageIgnore` annotation.',
			$stackPtr,
			'Missing'
		);
	}
}
