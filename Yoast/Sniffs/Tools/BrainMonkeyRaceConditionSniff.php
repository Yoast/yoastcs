<?php

namespace YoastCS\Yoast\Sniffs\Tools;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;
use PHPCSUtils\Utils\Conditions;
use PHPCSUtils\Utils\FunctionDeclarations;
use PHPCSUtils\Utils\PassedParameters;
use PHPCSUtils\Utils\Scopes;
use PHPCSUtils\Utils\TextStrings;

/**
 * Sniff to detect a particular nasty race condition which can occur in tests using the BrainMonkey utilities.
 *
 * @link https://github.com/Yoast/yoastcs/issues/264
 *
 * @since 2.3.0
 * @since 3.0.0 This sniff no longer extends the WPCS abstract Sniff class.
 */
final class BrainMonkeyRaceConditionSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		return [ \T_STRING ];
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

		if ( \strtolower( $tokens[ $stackPtr ]['content'] ) !== 'expect' ) {
			return;
		}

		$nextNonEmpty = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
		if ( $nextNonEmpty === false || $tokens[ $nextNonEmpty ]['code'] !== \T_OPEN_PARENTHESIS ) {
			// Definitely not a function call.
			return;
		}

		$prevNonEmpty = $phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true );
		if ( $prevNonEmpty === false
			|| isset( Collections::objectOperators()[ $tokens[ $prevNonEmpty ]['code'] ] )
			|| $tokens[ $prevNonEmpty ]['code'] === \T_FUNCTION
		) {
			// Method call or function declaration, not a function call.
			return;
		}

		$functionToken = Conditions::getLastCondition( $phpcsFile, $stackPtr, [ \T_FUNCTION ] );
		if ( $functionToken === false ) {
			return;
		}

		if ( Scopes::isOOMethod( $phpcsFile, $functionToken ) === false ) {
			return;
		}

		// Check that this is an expect() for one of the hook functions.
		$param = PassedParameters::getParameter( $phpcsFile, $stackPtr, 1, 'function_name' );
		if ( empty( $param ) ) {
			return;
		}

		$expected   = Tokens::$emptyTokens;
		$expected[] = \T_CONSTANT_ENCAPSED_STRING;

		$hasUnexpected = $phpcsFile->findNext( $expected, $param['start'], ( $param['end'] + 1 ), true );
		if ( $hasUnexpected !== false ) {
			return;
		}

		$text        = $phpcsFile->findNext( Tokens::$emptyTokens, $param['start'], ( $param['end'] + 1 ), true );
		$textContent = TextStrings::stripQuotes( $tokens[ $text ]['content'] );
		if ( $textContent !== 'apply_filters' && $textContent !== 'do_action' ) {
			return;
		}

		// Now walk the contents of the function declaration to see if we can find the other function call.
		if ( isset( $tokens[ $functionToken ]['scope_opener'], $tokens[ $functionToken ]['scope_closer'] ) === false ) {
			// We don't know the start or end of the function.
			return;
		}

		$targetContent = 'expectdone';
		if ( $textContent === 'apply_filters' ) {
			$targetContent = 'expectapplied';
		}

		$start = $tokens[ $functionToken ]['scope_opener'];
		$end   = $tokens[ $functionToken ]['scope_closer'];

		for ( $i = $start; $i < $end; $i++ ) {
			if ( $tokens[ $i ]['code'] !== \T_STRING ) {
				continue;
			}

			if ( \strtolower( $tokens[ $i ]['content'] ) !== $targetContent ) {
				continue;
			}

			// Make sure it is a function call.
			$next = $phpcsFile->findNext( Tokens::$emptyTokens, ( $i + 1 ), null, true );
			if ( $next === false || $tokens[ $next ]['code'] !== \T_OPEN_PARENTHESIS ) {
				continue;
			}

			// Okay, we have found the race condition. Throw error.
			$message = 'The %s() test method contains both a call to Monkey\Functions\expect( %s ), as well as a call to %s(). This causes a race condition which will cause the tests to fail. Only use one of these in a test.';
			$data    = [
				FunctionDeclarations::getName( $phpcsFile, $functionToken ),
				$tokens[ $text ]['content'],
				( $targetContent === 'expectdone' ) ? 'Monkey\Actions\expectDone' : 'Monkey\Filters\expectApplied',
			];

			$phpcsFile->addError( $message, $functionToken, 'Found', $data );
			break;
		}
	}
}
