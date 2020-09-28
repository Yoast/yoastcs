<?php

namespace YoastCS\Yoast\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\FunctionSpacingSniff as Squiz_FunctionSpacingSniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Verifies the space between methods.
 *
 * This differs from the upstream sniff in that this sniff will ignore functions
 * in the global namespace as those are often wrapped in an if clause which causes
 * a fixer conflict.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   1.0.0
 */
class FunctionSpacingSniff extends Squiz_FunctionSpacingSniff {

	/**
	 * The number of blank lines between functions.
	 *
	 * {@internal Upstream sniff defaults to 2.}}
	 *
	 * @var int
	 */
	public $spacing = 1;

	/**
	 * The number of blank lines before the first function in a class.
	 *
	 * {@internal Upstream sniff defaults to 2.}}
	 *
	 * @var int
	 */
	public $spacingBeforeFirst = 1;

	/**
	 * The number of blank lines after the last function in a class.
	 *
	 * {@internal Upstream sniff defaults to 2.}}
	 *
	 * @var int
	 */
	public $spacingAfterLast = 0;

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void|int Optionally returns stack pointer to skip to.
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		// Check that the function is nested in an OO structure (class, trait, interface).
		if ( $phpcsFile->hasCondition( $stackPtr, Tokens::$ooScopeTokens ) === false ) {
			return;
		}

		return parent::process( $phpcsFile, $stackPtr );
	}
}
