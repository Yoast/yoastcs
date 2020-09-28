<?php

namespace YoastCS\Yoast\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Verify namespace declarations.
 *
 * This sniff:
 * - Forbids the use of namespace declarations without a namespace name.
 * - Forbids the use of scoped namespace declarations.
 * - Forbids having more than one namespace declaration in a file.
 *
 * {@internal This sniff might be a good candidate for pulling upstream in PHPCS
 * itself. An issue to that effect should be opened to see if there is interest.}}
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   1.2.0
 */
class NamespaceDeclarationSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return (int|string)[]
	 */
	public function register() {
		return [
			\T_OPEN_TAG,
		];
	}

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

		$statements = [];

		while ( ( $stackPtr = $phpcsFile->findNext( \T_NAMESPACE, ( $stackPtr + 1 ) ) ) !== false ) {

			$next_non_empty = $phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
			if ( $next_non_empty === false ) {
				// Live coding or parse error.
				break;
			}

			if ( $tokens[ $next_non_empty ]['code'] === \T_NS_SEPARATOR ) {
				// Not a namespace declaration, but the use of the namespace keyword as operator.
				continue;
			}

			// OK, found a namespace declaration.
			$statements[] = $stackPtr;

			if ( isset( $tokens[ $stackPtr ]['scope_condition'] )
				&& $tokens[ $stackPtr ]['scope_condition'] === $stackPtr
			) {
				// Scoped namespace declaration.
				$phpcsFile->addError(
					'Scoped namespace declarations are not allowed.',
					$stackPtr,
					'ScopedForbidden'
				);
			}

			if ( $tokens[ $next_non_empty ]['code'] === \T_SEMICOLON
				|| $tokens[ $next_non_empty ]['code'] === \T_OPEN_CURLY_BRACKET
			) {
				// Namespace declaration without namespace name (= global namespace).
				$phpcsFile->addError(
					'Namespace declarations without a namespace name are not allowed.',
					$stackPtr,
					'NoNameForbidden'
				);
			}
		}

		$count = \count( $statements );
		if ( $count > 1 ) {
			$data = [
				$count,
				$tokens[ $statements[0] ]['line'],
			];

			for ( $i = 1; $i < $count; $i++ ) {
				$phpcsFile->addError(
					'There should be only one namespace declaration per file. Found %d namespace declarations. The first declaration was found on line %d',
					$statements[ $i ],
					'MultipleFound',
					$data
				);
			}
		}

		return ( $phpcsFile->numTokens + 1 );
	}
}
