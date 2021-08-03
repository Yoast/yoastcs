<?php

namespace YoastCS\Yoast\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Verifies that all test functions have at least one @covers tag.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   1.3.0
 */
class TestsHaveCoversTagSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return (int|string)[]
	 */
	public function register() {
		return [
			\T_CLASS,
			\T_FUNCTION,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void|int Optionally returns a stack pointer. This sniff will not be
	 *                  called again on the current file until the returned stack
	 *                  pointer is reached.
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( $tokens[ $stackPtr ]['code'] === \T_CLASS ) {
			return $this->process_class( $phpcsFile, $stackPtr );
		}

		if ( $tokens[ $stackPtr ]['code'] === \T_FUNCTION ) {
			return $this->process_function( $phpcsFile, $stackPtr );
		}
	}

	/**
	 * Processes the docblock for a class token.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void|int If covers annotations were found (or this is not a test class),
	 *                  will return the stack pointer to the end of the class.
	 */
	protected function process_class( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$name   = $phpcsFile->getDeclarationName( $stackPtr );

		if ( \substr( $name, -4 ) !== 'Test'
			&& \substr( $name, -8 ) !== 'TestCase'
			&& \substr( $name, 0, 4 ) !== 'Test'
		) {
			// Not a test class.
			if ( isset( $tokens[ $stackPtr ]['scope_closer'] ) ) {
				// No need to examine the methods in this class.
				return $tokens[ $stackPtr ]['scope_closer'];
			}

			return;
		}

		// @todo: Once PHPCSUtils is out, replace with call to new findCommentAboveOOStructure() method.
		$ignore = [
			\T_WHITESPACE => \T_WHITESPACE,
			\T_ABSTRACT   => \T_ABSTRACT,
			\T_FINAL      => \T_FINAL,
		];

		$commentEnd = $stackPtr;
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
			// Class without (proper) docblock. Not our concern.
			return;
		}

		$commentStart = $tokens[ $commentEnd ]['comment_opener'];

		$foundCovers        = false;
		$foundCoversNothing = false;
		foreach ( $tokens[ $commentStart ]['comment_tags'] as $tag ) {
			if ( $tokens[ $tag ]['content'] === '@covers' ) {
				$foundCovers = true;
			}

			if ( $tokens[ $tag ]['content'] === '@coversNothing' ) {
				$foundCoversNothing = true;
			}
		}

		if ( $foundCovers === true || $foundCoversNothing === true ) {
			// Class level tags found, valid for all methods. No need to examine the individual methods.
			if ( isset( $tokens[ $stackPtr ]['scope_closer'] ) ) {
				return $tokens[ $stackPtr ]['scope_closer'];
			}
		}
	}

	/**
	 * Processes the docblock for a function token.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	protected function process_function( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		// @todo: Once PHPCSUtils is out, replace with call to new findCommentAboveFunction() method.
		$ignore                  = Tokens::$methodPrefixes;
		$ignore[ \T_WHITESPACE ] = \T_WHITESPACE;

		$commentEnd = $stackPtr;
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

		$foundTest          = false;
		$foundCovers        = false;
		$foundCoversNothing = false;

		if ( $tokens[ $commentEnd ]['code'] === \T_DOC_COMMENT_CLOSE_TAG ) {
			$commentStart = $tokens[ $commentEnd ]['comment_opener'];

			foreach ( $tokens[ $commentStart ]['comment_tags'] as $tag ) {
				if ( $tokens[ $tag ]['content'] === '@test' ) {
					$foundTest = true;
					continue;
				}

				if ( $tokens[ $tag ]['content'] === '@covers' ) {
					$foundCovers = true;
					continue;
				}

				if ( $tokens[ $tag ]['content'] === '@coversNothing' ) {
					$foundCoversNothing = true;
					continue;
				}
			}
		}

		$name = $phpcsFile->getDeclarationName( $stackPtr );
		if ( \stripos( $name, 'test' ) !== 0 && $foundTest === false ) {
			// Not a test method.
			return;
		}

		$method_props = $phpcsFile->getMethodProperties( $stackPtr );
		if ( $method_props['is_abstract'] === true ) {
			// Abstract test method, not implemented.
			return;
		}

		if ( $foundCovers === true || $foundCoversNothing === true ) {
			// Docblock contains one or more @covers tags.
			return;
		}

		$msg  = 'Each test function should have at least one @covers tag annotating which class/method/function is being tested.';
		$data = [ $name ];

		if ( $tokens[ $commentEnd ]['code'] === \T_DOC_COMMENT_CLOSE_TAG ) {
			$msg .= ' Tag missing for function %s().';
			$code = 'Missing';
		}
		else {
			$msg .= ' Test function %s() does not have a docblock with a @covers tag.';
			$code = 'NoDocblock';
		}

		$phpcsFile->addError( $msg, $stackPtr, $code, $data );
	}
}
