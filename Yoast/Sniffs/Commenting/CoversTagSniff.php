<?php

namespace YoastCS\Yoast\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPCSUtils\Utils\GetTokensAsString;

/**
 * Verifies that a @covers tag annotation follows a format supported by PHPUnit.
 *
 * Also ensures that:
 * - each @covers tag has an annotation;
 * - there are no duplicate @covers tags;
 * - there are no duplicate @coversNothing tags;
 * - a method does not have both a @covers as well as a @coversNothing tag;
 * - deprecated @covers tag formats are flagged. (since 3.0.0)
 *
 * @since 1.3.0
 */
final class CoversTagSniff implements Sniff {

	/**
	 * Regex to check for valid content of a @covers tags.
	 *
	 * @var string
	 */
	private const VALID_CONTENT_REGEX = '(?:\\\\?(?:(?<OOName>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\\\\)*(?P>OOName)(?:<extended>|::<[!]?(?:public|protected|private)>|::(?<functionName>(?!public$|protected$|private$)(?P>OOName)))?|::(?P>functionName)|::<[!]?(?:public|protected|private)>|\\\\?(?:(?P>OOName)\\\\)+(?P>functionName))';

	/**
	 * Regex to check for deprecated `@covers ClassName<extended>` tag format.
	 *
	 * @link https://github.com/sebastianbergmann/phpunit/issues/3630 PHPUnit 9.0 deprecation.
	 * @link https://github.com/sebastianbergmann/phpunit/issues/3631 PHPUnit 10.0 removal.
	 *
	 * @var string
	 */
	private const DEPRECATED_FORMAT_EXTENDED = '`^(?:\\\\)?(?:(?<OOName>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\\\\)*(?P>OOName)<extended>$`';

	/**
	 * Regex to check for deprecated `@covers *::<[!]public|protected|private>` tag format.
	 *
	 * @link https://github.com/sebastianbergmann/phpunit/issues/3630 PHPUnit 9.0 deprecation.
	 * @link https://github.com/sebastianbergmann/phpunit/issues/3631 PHPUnit 10.0 removal.
	 *
	 * @var string
	 */
	private const DEPRECATED_FORMAT_VISIBILITY = '`::<[!]?(?:public|protected|private)>$`';

	/**
	 * Base error message.
	 *
	 * Will be enhanced during the run.
	 *
	 * @var string
	 */
	private const ERROR_MSG = 'Invalid @covers annotation found.';

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array<int|string>
	 */
	public function register() {
		return [
			\T_DOC_COMMENT_OPEN_TAG,
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

		/*
		 * Find all relevant tags and check for common mistakes in the tag format.
		 */
		$firstCoversTag    = false;
		$coversTags        = [];
		$coversNothingTags = [];
		foreach ( $tokens[ $stackPtr ]['comment_tags'] as $tag ) {
			if ( $tokens[ $tag ]['content'] === '@coversNothing' ) {
				$coversNothingTags[] = $tag;
				continue;
			}

			if ( $tokens[ $tag ]['content'] !== '@covers' ) {
				continue;
			}

			if ( $firstCoversTag === false ) {
				$firstCoversTag = $tag;
			}

			// Found a @covers tag.
			$next = $phpcsFile->findNext( \T_DOC_COMMENT_WHITESPACE, ( $tag + 1 ), null, true );
			if ( $next === false // Shouldn't be possible.
				|| $tokens[ $next ]['code'] !== \T_DOC_COMMENT_STRING
				|| $tokens[ $next ]['line'] !== $tokens[ $tag ]['line']
			) {
				$phpcsFile->addError(
					'A @covers tag must indicate which class/function/method is being covered by the test',
					$tag,
					'Empty'
				);

				continue;
			}

			$annotation                 = $tokens[ $next ]['content'];
			$coversTags[ "$tag-$next" ] = $annotation;

			// Check for deprecated/removed @covers formats.
			if ( \preg_match( self::DEPRECATED_FORMAT_EXTENDED, $annotation ) === 1
				|| \preg_match( self::DEPRECATED_FORMAT_VISIBILITY, $annotation ) === 1
			) {
				$warning  = 'Use of the "ClassName<*>" type values for @covers annotations has been deprecated in PHPUnit 9.0';
				$warning .= ' and support has been removed in PHPUnit 10.0. Found: %s';
				$data     = [ $annotation ];
				$phpcsFile->addWarning( $warning, $next, 'RemovedFormat', $data );
			}

			if ( \preg_match( '`^' . self::VALID_CONTENT_REGEX . '$`', $annotation ) === 1 ) {
				continue;
			}

			/*
			 * Account for a number of common "mistakes".
			 */

			$errorThrown = false;

			// Check for Union/Intersect types.
			if ( \strpos( $annotation, '&' ) !== false ) {
				if ( $this->fixAnnotationToSplit( $phpcsFile, $next, 'IntersectFound', '&' ) === true ) {
					continue;
				}

				$errorThrown = true;
			}

			if ( \strpos( $annotation, '|' ) !== false ) {
				if ( $this->fixAnnotationToSplit( $phpcsFile, $next, 'UnionFound', '|' ) === true ) {
					continue;
				}

				$errorThrown = true;
			}

			// Parentheses/Braces at the end of the annotation.
			$expected = \rtrim( $annotation, '(){} ' );
			if ( $this->fixSimpleError( $phpcsFile, $next, $expected, 'InvalidBraces' ) === true ) {
				$errorThrown = true;
			}

			// Incorrect `<public|protected|private>` annotation.
			if ( \preg_match( '`::[{(\[]?(!)?(public|protected|private)[})\]]?`', $annotation, $matches ) === 1 ) {
				$replacement = '::<' . $matches[1] . $matches[2] . '>';
				$expected    = \str_replace( $matches[0], $replacement, $annotation );

				if ( $this->fixSimpleError( $phpcsFile, $next, $expected, 'InvalidFunctionGroup' ) === true ) {
					$errorThrown = true;
				}
			}

			if ( $errorThrown === true ) {
				// We've already thrown an error. No need for duplicates.
				continue;
			}

			// Throw a generic error for all other invalid annotations.
			$error  = self::ERROR_MSG;
			$error .= ' Check the PHPUnit documentation to see which annotations are supported. Found: %s';
			$data   = [ $annotation ];
			$phpcsFile->addError( $error, $next, 'Invalid', $data );
		}

		/*
		 * Check that a docblock doesn't contain both `@covers` tags as well as `@coversNothing` tag(s).
		 */
		$coversNothingCount = \count( $coversNothingTags );
		if ( $firstCoversTag !== false && $coversNothingCount > 0 ) {
			$error = 'A test can\'t both cover something as well as cover nothing. First @coversNothing tag encountered on line %d; first @covers tag encountered on line %d';
			$data  = [
				$tokens[ $coversNothingTags[0] ]['line'],
				$tokens[ $firstCoversTag ]['line'],
			];

			$phpcsFile->addError( $error, $tokens[ $stackPtr ]['comment_closer'], 'Contradictory', $data );
		}

		/*
		 * Check for duplicate `@coversNothing` tags.
		 */
		if ( $coversNothingCount > 1 ) {
			$error      = 'Only one @coversNothing tag allowed per test';
			$code       = 'DuplicateCoversNothing';
			$removeTags = [];
			foreach ( $coversNothingTags as $ptr ) {
				$next = ( $ptr + 1 );
				if ( $tokens[ $next ]['code'] === \T_DOC_COMMENT_WHITESPACE
					&& $tokens[ $next ]['content'] === $phpcsFile->eolChar
				) {
					// No comment, ok to remove.
					$removeTags[] = $ptr;
				}
			}

			$removalCount = \count( $removeTags );
			if ( ( $coversNothingCount - $removalCount ) > 1 ) {
				// More than one tag had a comment.
				$phpcsFile->addError( $error, $tokens[ $stackPtr ]['comment_closer'], $code );
			}
			else {
				$fix = $phpcsFile->addFixableError( $error, $tokens[ $stackPtr ]['comment_closer'], $code );
				if ( $fix === true ) {
					$skipFirst = ( $coversNothingCount === $removalCount );

					$phpcsFile->fixer->beginChangeset();

					foreach ( $removeTags as $key => $ptr ) {
						if ( $skipFirst === true && $key === 0 ) {
							// Let the first one remain if none of the tags has a comment.
							continue;
						}

						// Remove the whole line.
						for ( $i = ( $ptr + 1 ); $i >= 0; $i-- ) {
							if ( $tokens[ $i ]['line'] !== $tokens[ $ptr ]['line'] ) {
								break;
							}

							$phpcsFile->fixer->replaceToken( $i, '' );
						}
					}

					$phpcsFile->fixer->endChangeset();
				}
			}
		}

		/*
		 * Check for duplicate `@covers ...` tags.
		 */
		$coversCount = \count( $coversTags );
		if ( $coversCount > 1 ) {
			$unique = \array_unique( $coversTags );
			if ( \count( $unique ) !== $coversCount ) {
				$value_count = \array_count_values( $coversTags );
				$error       = 'Duplicate @covers tag found. First tag with the same annotation encountered on line %d';
				$code        = 'DuplicateCovers';
				foreach ( $value_count as $annotation => $count ) {
					if ( $count < 2 ) {
						continue;
					}

					$first = null;
					$data  = [];
					foreach ( $coversTags as $ptrs => $annot ) {
						if ( $annotation !== $annot ) {
							continue;
						}

						if ( $first === null ) {
							$first = \explode( '-', $ptrs );
							$data  = [ $tokens[ $first[0] ]['line'] ];
							continue;
						}

						$ptrs = \explode( '-', $ptrs );

						$fix = $phpcsFile->addFixableError( $error, (int) $ptrs[0], $code, $data );
						if ( $fix === true ) {
							$phpcsFile->fixer->beginChangeset();

							// Remove the whole line.
							for ( $i = (int) $ptrs[1]; $i >= 0; $i-- ) {
								if ( $tokens[ $i ]['line'] !== $tokens[ $ptrs[1] ]['line'] ) {
									if ( $tokens[ $i ]['code'] === \T_DOC_COMMENT_WHITESPACE
										&& $tokens[ $i ]['content'] === $phpcsFile->eolChar
									) {
										$phpcsFile->fixer->replaceToken( $i, '' );
									}
									break;
								}

								$phpcsFile->fixer->replaceToken( $i, '' );
							}

							$phpcsFile->fixer->endChangeset();
						}
					}
				}
			}
		}
	}

	/**
	 * Add a fixable error if a suitable alternative is available.
	 *
	 * @param File   $phpcsFile The file being scanned.
	 * @param int    $stackPtr  The position of the current token in the stack passed in $tokens.
	 * @param string $expected  The expected alternative annotation.
	 *                          This annotation might not be valid itself.
	 * @param string $errorCode The error code.
	 *
	 * @return bool Whether an error has been thrown or not.
	 */
	private function fixSimpleError( File $phpcsFile, $stackPtr, $expected, $errorCode ) {
		$tokens     = $phpcsFile->getTokens();
		$annotation = $tokens[ $stackPtr ]['content'];

		if ( $expected === $annotation
			|| \preg_match( '`^' . self::VALID_CONTENT_REGEX . '$`', $expected ) !== 1
		) {
			return false;
		}

		$error = self::ERROR_MSG . "\nExpected: `%s`\nFound:    `%s`";
		$data  = [
			$expected,
			$annotation,
		];

		$fix = $phpcsFile->addFixableError( $error, $stackPtr, $errorCode, $data );
		if ( $fix === true ) {
			$phpcsFile->fixer->replaceToken( $stackPtr, $expected );
		}

		return true;
	}

	/**
	 * Add a fixable error for a union/intersect @covers annotation.
	 *
	 * @param File   $phpcsFile The file being scanned.
	 * @param int    $stackPtr  The position of the current token in the stack passed in $tokens.
	 * @param string $errorCode The error code.
	 * @param string $separator The separator to split the annotation on.
	 *
	 * @return bool Whether to skip the rest of the annotation examination or not.
	 */
	private function fixAnnotationToSplit( File $phpcsFile, $stackPtr, $errorCode, $separator ) {
		$fix = $phpcsFile->addFixableError(
			'Each @covers annotation should reference only one covered structure',
			$stackPtr,
			$errorCode
		);

		if ( $fix === true ) {
			$tokens      = $phpcsFile->getTokens();
			$annotation  = $tokens[ $stackPtr ]['content'];
			$annotations = \explode( $separator, $annotation );
			$annotations = \array_map( 'trim', $annotations );
			$annotations = \array_filter( $annotations ); // Remove empties.

			$phpcsFile->fixer->beginChangeset();
			$phpcsFile->fixer->replaceToken( $stackPtr, '' );

			for ( $i = ( $stackPtr - 1 ); $i >= 0; $i-- ) {
				if ( $tokens[ $i ]['line'] !== $tokens[ $stackPtr ]['line'] ) {
					break;
				}

				$phpcsFile->fixer->replaceToken( $i, '' );
			}

			$stub        = GetTokensAsString::origContent( $phpcsFile, $i, ( $stackPtr - 1 ) );
			$replacement = '';
			foreach ( $annotations as $annotation ) {
				$replacement .= $stub . $annotation;
			}

			$phpcsFile->fixer->replaceToken( $i, $replacement );
			$phpcsFile->fixer->endChangeset();

			return true;
		}

		return false;
	}
}
