<?php

namespace YoastCS\Yoast\Sniffs\Yoast;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\Namespaces;
use PHPCSUtils\Utils\PassedParameters;
use WordPressCS\WordPress\AbstractFunctionRestrictionsSniff;

/**
 * Discourages the use of the PHP and WP native [wp_]json_encode() functions in favour of a Yoast native alternative.
 *
 * @since 1.3.0
 * @since 3.0.0 Renamed from `AlternativeFunctionsSniff` to `JsonEncodeAlternativeSniff`.
 */
final class JsonEncodeAlternativeSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * Name of the replacement function (method).
	 *
	 * @since 3.0.0
	 *
	 * @var string
	 */
	private const REPLACEMENT = 'WPSEO_Utils::format_json_encode';

	/**
	 * Function call parameter details.
	 *
	 * @since 3.0.0
	 *
	 * @var array<string, string|array<string>> Function names as the keys and the name of the first declared parameter
	 *                                          as the value.
	 *                                          There can be multiple parameter names if the parameter
	 *                                          was renamed over time.
	 */
	private const PARAM_INFO = [
		'json_encode'    => 'value',

		/*
		 * The current parameter name is `$data`, but this is expected to be changed to `$value` in WP 6.5.
		 * See: https://core.trac.wordpress.org/ticket/59630
		 */
		'wp_json_encode' => [ 'data', 'value' ],
	];

	/**
	 * Groups of functions to restrict.
	 *
	 * @return array<string, array<string, array<string>>>
	 */
	public function getGroups() {
		return [
			'json_encode' => [
				'functions' => [
					'json_encode',
					'wp_json_encode',
				],
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
		$error      = 'Detected a call to %s(). Use %s() instead.';
		$error_code = 'Found';
		$data       = [
			$matched_content,
			self::REPLACEMENT,
		];

		$params = PassedParameters::getParameters( $this->phpcsFile, $stackPtr );

		/*
		 * If no parameters were passed, we can safely replace the function call, even though
		 * the function call itself, as-is, is not correct/working (but that's not the concern of
		 * this sniff).
		 */
		if ( empty( $params ) ) {
			/*
			 * Make sure this is not a PHP 8.1+ first class callable. If it is, throw the error, but don't autofix.
			 */
			$ignore                        = Tokens::$emptyTokens;
			$ignore[ \T_OPEN_PARENTHESIS ] = \T_OPEN_PARENTHESIS;

			$first_in_call = $this->phpcsFile->findNext( $ignore, ( $stackPtr + 1 ), null, true );
			if ( $first_in_call !== false && $this->tokens[ $first_in_call ]['code'] === \T_ELLIPSIS ) {
				$error_code .= 'InFirstClassCallable';
				$this->phpcsFile->addError( $error, $stackPtr, $error_code, $data );
				return;
			}

			$fix = $this->phpcsFile->addFixableError( $error, $stackPtr, $error_code, $data );
			if ( $fix === true ) {
				$this->fix_it( $stackPtr );
			}

			return;
		}

		/*
		 * If there are function parameters, we need to verify that only the first ($value) parameter
		 * was passed, taking PHP 8.0+ function calls with named parameters into account.
		 *
		 * We also need to check for parameter unpacking being used as in that case, the
		 * parameter count will be unreliable.
		 */
		$value_param = PassedParameters::getParameterFromStack( $params, 1, self::PARAM_INFO[ $matched_content ] );
		if ( \is_array( $value_param ) && \count( $params ) === 1 ) {
			// @phpstan-ignore binaryOp.invalid, argument.type (The passed value will only ever be an integer, PHPStan just doesn't know the shape of the array.)
			$first_token = $this->phpcsFile->findNext( Tokens::$emptyTokens, $value_param['start'], ( $value_param['end'] + 1 ), true );
			if ( $first_token === false || $this->tokens[ $first_token ]['code'] !== \T_ELLIPSIS ) {
				/*
				 * Okay, so this is a function call with only the first/$value parameter passed.
				 * This can be safely replaced.
				 */
				$fix = $this->phpcsFile->addFixableError( $error, $stackPtr, $error_code, $data );
				if ( $fix === true ) {
					$this->fix_it( $stackPtr, $value_param );
				}

				return;
			}
		}

		/*
		 * In all other cases, we cannot auto-fix, only flag.
		 */
		$error_code .= 'WithAdditionalParams';

		$this->phpcsFile->addError( $error, $stackPtr, $error_code, $data );
	}

	/**
	 * Auto-fix the function call to use the replacement function.
	 *
	 * @since 3.0.0
	 *
	 * @param int                             $stackPtr    The position of the current token in the stack.
	 * @param array<string, int|string>|false $value_param Optional. Parameter information for the first/$value
	 *                                                     parameter if available, or false if not.
	 *
	 * @return void
	 */
	private function fix_it( $stackPtr, $value_param = false ) {
		$this->phpcsFile->fixer->beginChangeset();

		// Remove potential leading namespace separator for fully qualified function call.
		$prev = $this->phpcsFile->findPrevious( Tokens::$emptyTokens, ( $stackPtr - 1 ), null, true );
		if ( $prev !== false && $this->tokens[ $prev ]['code'] === \T_NS_SEPARATOR ) {
			$this->phpcsFile->fixer->replaceToken( $prev, '' );
		}

		// Replace the function call with a, potentially fully qualified, call to the replacement.
		$namespaced = Namespaces::determineNamespace( $this->phpcsFile, $stackPtr );
		if ( empty( $namespaced ) ) {
			$this->phpcsFile->fixer->replaceToken( $stackPtr, self::REPLACEMENT );
		}
		else {
			$this->phpcsFile->fixer->replaceToken( $stackPtr, '\\' . self::REPLACEMENT );
		}

		if ( \is_array( $value_param ) && isset( $value_param['name_token'] ) ) {
			// Update the parameter name when the function call uses named parameters.
			// `$data` is the parameter name used in the WPSEO_Utils::format_json_encode() function.

			// @phpstan-ignore argument.type (The passed value will only ever be an integer, PHPStan just doesn't know the shape of the array.)
			$this->phpcsFile->fixer->replaceToken( $value_param['name_token'], 'data' );
		}

		$this->phpcsFile->fixer->endChangeset();
	}
}
