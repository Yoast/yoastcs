<?php
/**
 * YoastCS\Yoast\Sniffs\ControlStructures\IfElseDeclarationSniff.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace YoastCS\Yoast\Sniffs\ControlStructures;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Verifies that else statements are on a new line.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   0.1
 * @since   0.5 This class now uses namespaces and is no longer compatible with PHPCS 2.x.
 */
class IfElseDeclarationSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return array(
			T_ELSE,
			T_ELSEIF,
		);
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current
	 *                                               in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( isset( $tokens[ $stackPtr ]['scope_opener'] ) ) {
			$scope_open = $tokens[ $stackPtr ]['scope_opener'];
		}
		else {
			// Deal with "else if".
			$next = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
			if ( $tokens[ $next ]['code'] === T_IF && isset( $tokens[ $next ]['scope_opener'] ) ) {
				$scope_open = $tokens[ $next ]['scope_opener'];
			}
		}

		if ( ! isset( $scope_open ) || $tokens[ $scope_open ]['code'] === T_COLON ) {
			// No scope opener found or alternative syntax (not our concern).
			return;
		}

		$previous_scope_closer = $phpcsFile->findPrevious( T_CLOSE_CURLY_BRACKET, ( $stackPtr - 1 ) );

		if ( $tokens[ $previous_scope_closer ]['line'] === $tokens[ $stackPtr ]['line'] ) {
			$phpcsFile->addError(
				'%s statement must be on a new line',
				$stackPtr,
				'NewLine',
				array( ucfirst( $tokens[ $stackPtr ]['content'] ) )
			);
		}
		elseif ( $tokens[ $previous_scope_closer ]['column'] !== $tokens[ $stackPtr ]['column'] ) {
			$phpcsFile->addError(
				'%s statement not aligned with previous part of the control structure',
				$stackPtr,
				'Alignment',
				array( ucfirst( $tokens[ $stackPtr ]['content'] ) )
			);
		}

		$previous_non_empty = $phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true );

		if ( $previous_scope_closer !== $previous_non_empty ) {
			$error = 'Nothing but whitespace and comments allowed between closing bracket and %s statement, found "%s"';
			$data  = array(
				$tokens[ $stackPtr ]['content'],
				trim( $phpcsFile->getTokensAsString( ( $previous_scope_closer + 1 ), ( $stackPtr - ( $previous_scope_closer + 1 ) ) ) ),
			);
			$phpcsFile->addError( $error, $stackPtr, 'StatementFound', $data );
		}
	}
}
