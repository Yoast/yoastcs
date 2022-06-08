<?php

namespace YoastCS\Yoast\Sniffs\Tools;

use PHP_CodeSniffer\Util\Tokens;
use WordPressCS\WordPress\Sniff;

/**
 * Sniff to detect a particular nasty race condition which can occur in tests using the BrainMonkey utilities.
 *
 * @link https://github.com/Yoast/yoastcs/issues/264
 *
 * @since 2.3.0
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 */
final class BrainMonkeyRaceConditionSniff extends Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [ \T_STRING ];
	}

	/**
	 * Processes a sniff when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {
		if ( \strtolower( $this->tokens[ $stackPtr ]['content'] ) !== 'expect' ) {
			return;
		}

		$nextNonEmpty = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
		if ( $nextNonEmpty === false || $this->tokens[ $nextNonEmpty ]['code'] !== \T_OPEN_PARENTHESIS ) {
			// Definitely not a function call.
			return;
		}

		$prevNonEmpty = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true );
		if ( $prevNonEmpty === false
			|| $this->tokens[ $prevNonEmpty ]['code'] === \T_DOUBLE_COLON
			|| $this->tokens[ $prevNonEmpty ]['code'] === \T_OBJECT_OPERATOR
			|| $this->tokens[ $prevNonEmpty ]['type'] === 'T_NULLSAFE_OBJECT_OPERATOR'
			|| $this->tokens[ $prevNonEmpty ]['code'] === \T_FUNCTION
		) {
			// Method call or function declaration, not a function call.
			return;
		}

		// Make sure the token is in a class method.
		if ( empty( $this->tokens[ $stackPtr ]['conditions'] ) ) {
			return;
		}

		$conditions = $this->tokens[ $stackPtr ]['conditions'];
		$conditions = \array_reverse( $conditions, true );

		$seenOO        = false;
		$seenFn        = false;
		$functionToken = false;

		foreach ( $conditions as $token => $condition ) {
			if ( $seenFn === true ) {
				// First condition after the function condition MUST be OO, otherwise it's not a method.
				$seenOO = isset( Tokens::$ooScopeTokens[ $condition ] );
				break;
			}

			if ( $condition === \T_FUNCTION ) {
				$functionToken = $token;
				$seenFn        = true;
			}
		}

		if ( $seenFn === false || $seenOO === false || $functionToken === false ) {
			return;
		}

		// Check that this is an expect() for one of the hook functions.
		$params = $this->get_function_call_parameters( $stackPtr );
		if ( empty( $params ) ) {
			return;
		}

		$expected   = Tokens::$emptyTokens;
		$expected[] = \T_CONSTANT_ENCAPSED_STRING;

		$hasUnexpected = $this->phpcsFile->findNext( $expected, $params[1]['start'], ( $params[1]['end'] + 1 ), true );
		if ( $hasUnexpected !== false ) {
			return;
		}

		$text        = $this->phpcsFile->findNext( Tokens::$emptyTokens, $params[1]['start'], ( $params[1]['end'] + 1 ), true );
		$textContent = $this->strip_quotes( $this->tokens[ $text ]['content'] );
		if ( $textContent !== 'apply_filters' && $textContent !== 'do_action' ) {
			return;
		}

		// Now walk the contents of the function declaration to see if we can find the other function call.
		if ( isset( $this->tokens[ $functionToken ]['scope_opener'], $this->tokens[ $functionToken ]['scope_closer'] ) === false ) {
			// We don't know the start or end of the function.
			return;
		}

		$targetContent = 'expectdone';
		if ( $textContent === 'apply_filters' ) {
			$targetContent = 'expectapplied';
		}

		$start = $this->tokens[ $functionToken ]['scope_opener'];
		$end   = $this->tokens[ $functionToken ]['scope_closer'];

		for ( $i = $start; $i < $end; $i++ ) {
			if ( $this->tokens[ $i ]['code'] !== \T_STRING ) {
				continue;
			}

			if ( \strtolower( $this->tokens[ $i ]['content'] ) !== $targetContent ) {
				continue;
			}

			// Make sure it is a function call.
			$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $i + 1 ), null, true );
			if ( $next === false || $this->tokens[ $next ]['code'] !== \T_OPEN_PARENTHESIS ) {
				continue;
			}

			// Okay, we have found the race condition. Throw error.
			$message = 'The %s() test method contains both a call to Monkey\Functions\expect( %s ), as well as a call to %s(). This causes a race condition which will cause the tests to fail. Only use one of these in a test.';
			$data    = [
				$this->phpcsFile->getDeclarationName( $functionToken ),
				$this->tokens[ $text ]['content'],
				( $targetContent === 'expectdone' ) ? 'Monkey\Actions\expectDone' : 'Monkey\Filters\expectApplied',
			];

			$this->phpcsFile->addError( $message, $functionToken, 'Found', $data );
			break;
		}
	}
}
