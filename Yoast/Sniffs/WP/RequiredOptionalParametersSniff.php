<?php
/**
 * YoastCS\Yoast\Sniffs\WP\RequiredOptionalParameters.
 *
 * @package Yoast\YoastCS
 * @author  Jip Moors
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace YoastCS\Yoast\Sniffs\WP;

use WordPress\AbstractFunctionParameterSniff;

/**
 * Sniff that ensures the optional parameter for update_option() and add_option()
 * are passed because we want developers to be conscious about what is needed.
 *
 * @package Yoast\YoastCS
 * @author  Jip Moors
 *
 * @since   1.1.0
 */
class RequiredOptionalParametersSniff extends AbstractFunctionParameterSniff {

	/**
	 * The group name for this group of functions.
	 *
	 * @var string
	 */
	protected $group_name = 'wp_required_parameters';

	/**
	 * Functions this sniff is looking for.
	 *
	 * @var array Multidimensional array with parameter details.
	 *    $target_functions = array(
	 *        (string) Function name. => array(
	 *            (int) Target parameter position, 1-based. => array(
	 *                'name'       => (string)  The name of the argument that should be set.
	 *                'enforce'    => (boolean) If this should trigger an error or warning.
	 *                'allow_null' => (boolean) Whether `null` is seen as setting the optional value. Defaults to false.
	 *            )
	 *         )
	 *    );
	 */
	protected $target_functions = array(
		'update_option' => array(
			3 => array(
				'name'       => 'autoload',
				'enforce'    => false,
				'allow_null' => false,
			),
		),
		'add_option'    => array(
			4 => array(
				'name'       => 'autoload',
				'enforce'    => true,
				'allow_null' => false,
			),
		),
	);

	/**
	 * Process the parameters of a matched function.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param array  $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched.
	 * @param array  $parameters      Array with information about the parameters.
	 *
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		foreach ( $this->target_functions[ $matched_content ] as $position => $parameter_args ) {
			// Check that a valid parameter value is being passed.
			if ( $this->has_valid_parameter( $parameters, $position, $parameter_args ) ) {
				continue;
			}

			$message = '%s() is %s to be called with the "%s" argument at position %d%s.';

			$error_type = ( $parameter_args['enforce'] === true ) ? 'Required' : 'Optional';
			$error_null = $this->is_null_allowed( $parameter_args ) ? '' : 'NullDisallowed';

			$error_string = $matched_content . $error_type . 'Param' . ucfirst( $parameter_args['name'] ) . 'Missing' . $error_null;

			$code = $this->string_to_errorcode( $error_string );

			$data = array(
				$matched_content,
				( $parameter_args['enforce'] === true ) ? 'required' : 'expected',
				$parameter_args['name'],
				$position,
				( $this->is_null_allowed( $parameter_args ) ) ? '' : ', which cannot be "null"',
			);

			$is_error = $parameter_args['enforce'];
			if ( ! $is_error && ! $parameter_args['allow_null'] ) {
				$is_error = ( isset( $parameters[ $position ] ) && $parameters[ $position ]['raw'] === 'null' );
			}

			$this->addMessage( $message, $stackPtr, $is_error, $code, $data );
		}
	}

	/**
	 * Determines if a valid parameter has been provided.
	 *
	 * @param array $parameters     The parameters used to call the function.
	 * @param int   $position       The position of the parameter to check.
	 * @param array $parameter_args The configuration of the function check.
	 *
	 * @return bool True if a valid parameter has been given, False otherwise.
	 */
	protected function has_valid_parameter( $parameters, $position, $parameter_args ) {
		if ( ! isset( $parameters[ $position ] ) ) {
			return false;
		}

		if ( ! $this->is_null_allowed( $parameter_args ) ) {
			return false === $this->phpcsFile->findNext(
				\T_NULL,
				$parameters[ $position ]['start'],
				( $parameters[ $position ]['end'] + 1 )
			);
		}

		return true;
	}

	/**
	 * Determines if null is allowed for the specific function argument.
	 *
	 * @param array $parameter_args The arguments of the parameter supplied to the function.
	 *
	 * @return bool True if null is allowed, False otherwise.
	 */
	protected function is_null_allowed( $parameter_args ) {
		if ( ! isset( $parameter_args['allow_null'] ) ) {
			return false;
		}

		return $parameter_args['allow_null'];
	}
}
