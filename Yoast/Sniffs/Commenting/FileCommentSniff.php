<?php

namespace YoastCS\Yoast\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FileCommentSniff as Squiz_FileCommentSniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Namespaced files containing an OO structure do not need a file docblock in YoastCS.
 *
 * For namespaced files NOT containing an OO structure, a file docblock is permitted,
 * though not required.
 *
 * Note: Files without a namespace declaration do still need a file docblock.
 * This includes files which have a non-named namespace declaration which
 * falls back to the global namespace.
 *
 * {@internal At this moment - December 2018 -, the WordPress-Docs standard
 * uses the PHPCS Squiz standard commenting sniffs. For that reason, this Yoast
 * sniff extends the Squiz sniff as well.
 * Once WPCS introduces a WordPress native FileComment sniff, this sniff should
 * probably extend the WordPress native version instead.
 * If/when that comes into play, the code in the sniff needs to be reviewed to
 * see if it's still relevant to have this sniff and if so, if the sniff needs
 * adjustments.}}
 *
 * @since 1.2.0
 * @since 3.0.0 Behaviour for files containing an OO structure vs without has been updated
 *              to allow a file docblock when there is no OO structure.
 */
final class FileCommentSniff extends Squiz_FileCommentSniff {

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return int Stack pointer to skip the rest of the file.
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		/*
		 * Check if the file is namespaced. If not, fall through to the parent sniff.
		 */
		$namespace_token = $stackPtr;
		do {
			$namespace_token = $phpcsFile->findNext( Tokens::$emptyTokens, ( $namespace_token + 1 ), null, true );
			if ( $namespace_token === false ) {
				// No non-empty token found, fall through to parent sniff.
				return parent::process( $phpcsFile, $stackPtr );
			}

			if ( $tokens[ $namespace_token ]['code'] === \T_DECLARE ) {
				// Declare statement found. Find the end of it and skip over it.
				$end = $phpcsFile->findNext( [ \T_SEMICOLON, \T_OPEN_CURLY_BRACKET ], ( $namespace_token + 1 ), null, false, null, true );

				if ( $end !== false ) {
					$namespace_token = $end;
				}

				continue;
			}

			if ( $tokens[ $namespace_token ]['code'] !== \T_NAMESPACE ) {
				// No namespace found, fall through to parent sniff.
				return parent::process( $phpcsFile, $stackPtr );
			}

			// Stop searching if the next non-empty token wasn't a namespace token.
			break;
		} while ( true );

		$next_non_empty = $phpcsFile->findNext( Tokens::$emptyTokens, ( $namespace_token + 1 ), null, true );
		if ( $next_non_empty === false
			|| $tokens[ $next_non_empty ]['code'] === \T_SEMICOLON
			|| $tokens[ $next_non_empty ]['code'] === \T_OPEN_CURLY_BRACKET
			|| $tokens[ $next_non_empty ]['code'] === \T_NS_SEPARATOR
		) {
			// Either live coding, global namespace (i.e. not really namespaced) or namespace operator.
			// Fall through to parent sniff.
			return parent::process( $phpcsFile, $stackPtr );
		}

		/*
		 * As of here, we know we are in a namespaced file.
		 */

		$comment_start = $phpcsFile->findNext( \T_WHITESPACE, ( $stackPtr + 1 ), $namespace_token, true );

		if ( $comment_start === false || $tokens[ $comment_start ]['code'] !== \T_DOC_COMMENT_OPEN_TAG ) {
			// No file comment found, we're good.
			return ( $phpcsFile->numTokens + 1 );
		}

		// Respect phpcs:disable comments in the file docblock.
		if ( $phpcsFile->config->annotations === true && isset( $tokens[ $comment_start ]['comment_closer'] ) ) {
			for ( $i = ( $comment_start + 1 ); $i < $tokens[ $comment_start ]['comment_closer']; $i++ ) {
				if ( $tokens[ $i ]['code'] !== \T_PHPCS_DISABLE ) {
					continue;
				}

				if ( empty( $tokens[ $i ]['sniffCodes'] ) === true
					|| isset( $tokens[ $i ]['sniffCodes']['Yoast'] ) === true
					|| isset( $tokens[ $i ]['sniffCodes']['Yoast.Commenting'] ) === true
					|| isset( $tokens[ $i ]['sniffCodes']['Yoast.Commenting.FileComment'] ) === true
					|| isset( $tokens[ $i ]['sniffCodes']['Yoast.Commenting.FileComment.Unnecessary'] ) === true
				) {
					// Applicable disable annotation found.
					return ( $phpcsFile->numTokens + 1 );
				}
			}
		}

		/*
		 * Okay, so we have a file docblock in a namespaced file, now check if there is a named
		 * OO structure declaration.
		 */
		$find = Tokens::$ooScopeTokens;
		unset( $find[ \T_ANON_CLASS ] );
		$hasOODeclaration = $phpcsFile->findNext( $find, $next_non_empty );
		if ( $hasOODeclaration === false ) {
			/*
			 * This is a file which doesn't contain (just) an OO declaration.
			 * If there is a file docblock, allow it and check it like any other file docblock.
			 */
			return parent::process( $phpcsFile, $stackPtr );
		}

		$phpcsFile->addWarning(
			'A file containing a (named) namespace declaration does not need a file docblock',
			$comment_start,
			'Unnecessary'
		);

		return ( $phpcsFile->numTokens + 1 );
	}
}
