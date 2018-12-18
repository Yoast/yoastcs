<?php

namespace YoastCS\Yoast\Sniffs\Commenting;

use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FileCommentSniff as Squiz_FileCommentSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Namespaced files do not need a file docblock in YoastCS.
 *
 * - Non-namespaced files _do_ still need a file docblock.
 * - Files containing *scoped* namespaces also still need a file docblock.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   1.2.0
 */
class FileCommentSniff extends Squiz_FileCommentSniff {

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token
	 *                                               in the stack passed in $tokens.
	 *
	 * @return int Stack pointer to skip the rest of the file.
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$namespace_token = $phpcsFile->findNext( T_NAMESPACE, $stackPtr );
		if ( $namespace_token === false ) {
			// No namespace found, normal rules apply.
			return parent::process( $phpcsFile, $stackPtr );
		}

		$tokens = $phpcsFile->getTokens();

		$next_non_empty = $phpcsFile->findNext( Tokens::$emptyTokens, ( $namespace_token + 1 ), null, true );
		if ( $next_non_empty === false
			|| $tokens[ $next_non_empty ]['code'] === T_SEMICOLON
			|| $tokens[ $next_non_empty ]['code'] === T_OPEN_CURLY_BRACKET
			|| $tokens[ $next_non_empty ]['code'] === T_NS_SEPARATOR
		) {
			// Either live coding, global namespace (i.e. not really namespaced) or namespace operator.
			// Normal rules apply.
			return parent::process( $phpcsFile, $stackPtr );
		}


		$comment_start = $phpcsFile->findNext( T_WHITESPACE, ( $stackPtr + 1 ), $namespace_token, true );

		if ( $tokens[ $comment_start ]['code'] === T_DOC_COMMENT_OPEN_TAG ) {
			$phpcsFile->addWarning(
				'A namespaced file does not need a file docblock',
				$comment_start,
				'Unnecessary'
			);
		}

		return ( $phpcsFile->numTokens + 1 );
	}
}
