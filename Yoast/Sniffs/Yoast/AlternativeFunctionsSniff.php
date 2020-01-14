<?php

namespace YoastCS\Yoast\Sniffs\Yoast;

use WordPressCS\WordPress\AbstractFunctionRestrictionsSniff;

/**
 * Discourages the use of various functions and suggests (Yoast) alternatives.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   1.3.0
 */
class AlternativeFunctionsSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * Groups of functions to restrict.
	 *
	 * @return array
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
	 * @param string $matched_content The token content (function name) which was matched.
	 *
	 * @return void
	 */
	public function process_matched_token( $stackPtr, $group_name, $matched_content ) {

		$replacement = '';
		if ( isset( $this->groups[ $group_name ]['replacement'] ) ) {
			$replacement = $this->groups[ $group_name ]['replacement'];
		}

		$fixable    = true;
		$message    = $this->groups[ $group_name ]['message'];
		$is_error   = ( $this->groups[ $group_name ]['type'] === 'error' );
		$error_code = $this->string_to_errorcode( $group_name . '_' . $matched_content );
		$data       = [
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
				if ( $this->get_function_call_parameter_count( $stackPtr ) !== 1 ) {
					$fixable     = false;
					$error_code .= 'WithAdditionalParams';
				}

				break;
		}

		if ( $fixable === false ) {
			$this->addMessage( $message, $stackPtr, $is_error, $error_code, $data );
			return;
		}

		$fix = $this->addFixableMessage( $message, $stackPtr, $is_error, $error_code, $data );
		if ( $fix === true ) {
			$namespaced = $this->determine_namespace( $stackPtr );

			if ( empty( $namespaced ) || empty( $replacement ) ) {
				$this->phpcsFile->fixer->replaceToken( $stackPtr, $replacement );
			}
			else {
				$this->phpcsFile->fixer->replaceToken( $stackPtr, '\\' . $replacement );
			}
		}
	}
}
