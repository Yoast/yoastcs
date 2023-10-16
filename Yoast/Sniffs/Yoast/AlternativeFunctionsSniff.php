<?php

namespace YoastCS\Yoast\Sniffs\Yoast;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\MessageHelper;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\PassedParameters;
use WordPressCS\WordPress\AbstractFunctionRestrictionsSniff;

/**
 * Discourages the use of various functions and suggests (Yoast) alternatives.
 *
 * @since 1.3.0
 */
final class AlternativeFunctionsSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * Groups of functions to restrict.
	 *
	 * @return array<string, array<string, string|string[]>>
	 */
	public function getGroups() {
		return [
			'json_encode' => [
				'type'        => 'error',
				'message'     => 'Detected a call to %s(). Use %s() instead.',
				'functions'   => [
					'json_encode',
					'wp_json_encode',
				],
				'replacement' => 'WPSEO_Utils::format_json_encode',
			],
		];
	}

	/**
	 * Process a matched token.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 *
	 * @return void
	 */
	public function process_matched_token( $stackPtr, $group_name, $matched_content ) {

		$replacement = ( $this->groups[ $group_name ]['replacement'] ?? '' );
		$fixable     = true;
		$message     = $this->groups[ $group_name ]['message'];
		$is_error    = ( $this->groups[ $group_name ]['type'] === 'error' );
		$error_code  = MessageHelper::stringToErrorcode( $group_name . '_' . $matched_content );
		$data        = [
			$matched_content,
			$replacement,
		];

		/*
		 * Deal with specific situations.
		 */
		switch ( $matched_content ) {
			case 'json_encode':
			case 'wp_json_encode':
				/*
				 * The function `WPSEO_Utils:format_json_encode()` is only a valid alternative
				 * when only the first parameter is passed.
				 */
				if ( PassedParameters::getParameterCount( $this->phpcsFile, $stackPtr ) !== 1 ) {
					$fixable     = false;
					$error_code .= 'WithAdditionalParams';
				}

				break;
		}

		if ( $fixable === false ) {
			MessageHelper::addMessage( $this->phpcsFile, $message, $stackPtr, $is_error, $error_code, $data );
			return;
		}

		$fix = MessageHelper::addFixableMessage( $this->phpcsFile, $message, $stackPtr, $is_error, $error_code, $data );
		if ( $fix === true ) {
			$this->phpcsFile->fixer->beginChangeset();

			// Remove potential leading namespace separator for fully qualified function call.
			$prev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true );
			if ( $this->tokens[ $prev ]['code'] === \T_NS_SEPARATOR ) {
				$this->phpcsFile->fixer->replaceToken( $prev, '' );
			}

			// Replace the function call with a, potentially fully qualified, call to the replacement.
			$namespaced = Namespaces::determineNamespace( $this->phpcsFile, $stackPtr );
			if ( empty( $namespaced ) || empty( $replacement ) ) {
				$this->phpcsFile->fixer->replaceToken( $stackPtr, $replacement );
			}
			else {
				$this->phpcsFile->fixer->replaceToken( $stackPtr, '\\' . $replacement );
			}

			$this->phpcsFile->fixer->endChangeset();
		}
	}
}
