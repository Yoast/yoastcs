<?php
/**
 * YoastCS\Yoast\Sniffs\WhiteSpace\FunctionSpacingSniff.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace YoastCS\Yoast\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\FunctionSpacingSniff as Squiz_FunctionSpacingSniff;
use PHP_CodeSniffer\Files\File;
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
	 * @var integer
	 */
	public $spacing = 1;

	/**
	 * The number of blank lines before the first function in a class.
	 *
	 * {@internal Upstream sniff defaults to 2.}}
	 *
	 * @var integer
	 */
	public $spacingBeforeFirst = 1;

	/**
	 * The number of blank lines after the last function in a class.
	 *
	 * {@internal Upstream sniff defaults to 2.}}
	 *
	 * @var integer
	 */
	public $spacingAfterLast = 0;

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

		// Check that the function is nested in an OO structure (class, trait, interface).
		if ( $phpcsFile->hasCondition( $stackPtr, Tokens::$ooScopeTokens ) === false ) {
			return;
		}

		return parent::process( $phpcsFile, $stackPtr );
	}
}
