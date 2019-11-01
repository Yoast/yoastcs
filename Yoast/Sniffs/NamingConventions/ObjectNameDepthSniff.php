<?php

namespace YoastCS\Yoast\Sniffs\NamingConventions;

use WordPressCS\WordPress\Sniff as WPCS_Sniff;

/**
 * Check the number of words in object names declared within a namespace.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   1.4.0
 */
class ObjectNameDepthSniff extends WPCS_Sniff {

	/**
	 * Maximum number of words.
	 *
	 * The maximum number of words an object name should consist of, each
	 * separated by an underscore.
	 *
	 * If the name consists of more words, an ERROR will be thrown.
	 *
	 * @var int
	 */
	public $max_words = 3;

	/**
	 * Recommended maximum number of words.
	 *
	 * The recommended maximum number of words an object name should consist of, each
	 * separated by an underscore.
	 *
	 * If the name consists of more words, a WARNING will be thrown.
	 *
	 * @var int
	 */
	public $recommended_max_words = 3;

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token
	 *                      in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process_token( $stackPtr ) {

		// Check whether we are in a namespace or not.
		if ( $this->determine_namespace( $stackPtr ) === '' ) {
			return;
		}

		$object_name = $this->phpcsFile->getDeclarationName( $stackPtr );
		if ( empty( $object_name ) ) {
			return;
		}

		$parts      = explode( '_', $object_name );
		$part_count = count( $parts );

		if ( $part_count <= $this->recommended_max_words && $part_count <= $this->max_words ) {
			return;
		}

		// Check if the class is deprecated.
		$find = [
			T_ABSTRACT   => T_ABSTRACT,
			T_FINAL      => T_FINAL,
			T_WHITESPACE => T_WHITESPACE,
		];

		$comment_end = $this->phpcsFile->findPrevious( $find, ( $stackPtr - 1 ), null, true );
		if ( $this->tokens[ $comment_end ]['code'] === T_DOC_COMMENT_CLOSE_TAG ) {
			// Only check if the class has a docblock.
			$comment_start = $this->tokens[ $comment_end ]['comment_opener'];
			foreach ( $this->tokens[ $comment_start ]['comment_tags'] as $tag ) {
				if ( $this->tokens[ $tag ]['content'] === '@deprecated' ) {
					// Deprecated class, ignore.
					return;
				}
			}
		}

		// Active class.
		$object_type = 'a ' . $this->tokens[ $stackPtr ]['content'];
		if ( $this->tokens[ $stackPtr ]['code'] === \T_INTERFACE ) {
			$object_type = 'an ' . $this->tokens[ $stackPtr ]['content'];
		}

		if ( $part_count > $this->max_words ) {
			$error = 'The name of %s is not allowed to consist of more than %d words. Words found: %d in %s';
			$data  = [
				$object_type,
				$this->max_words,
				$part_count,
				$object_name,
			];

			$this->phpcsFile->addError( $error, $stackPtr, 'MaxExceeded', $data );
		}
		elseif ( $part_count > $this->recommended_max_words ) {
			$error = 'The name of %s should not consist of more than %d words. Words found: %d in %s';
			$data  = [
				$object_type,
				$this->recommended_max_words,
				$part_count,
				$object_name,
			];

			$this->phpcsFile->addWarning( $error, $stackPtr, 'TooLong', $data );
		}
	}
}
