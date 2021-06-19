<?php

namespace YoastCS\Yoast\Sniffs\NamingConventions;

use PHP_CodeSniffer\Util\Tokens;
use WordPressCS\WordPress\Sniffs\NamingConventions\ValidHookNameSniff as WPCS_ValidHookNameSniff;
use YoastCS\Yoast\Utils\CustomPrefixesTrait;

/**
 * Verify hook names.
 *
 * Per the WPCS native sniff:
 * Use lowercase letters in action and filter names. Separate words via underscores.
 *
 * In contrast to the WPCS native sniff, this Yoast specific version of the sniff
 * allows for a plugin specific prefix in ProperCase, like `Yoast\WP\Plugin\`, while
 * still demanding lowercase and underscore separators for the rest of the hook name
 * and for all hooks when no plugin specific prefixes have been passed.
 *
 * In addition to the WPCS native sniff:
 * Check the number of words in hook names and the use of the correct prefix-type,
 * but only when plugin specific prefixes have been passed.
 *
 * {@internal For now allows for an array of both old-style as well as new-style
 *            prefixes during the transition period.
 *            Once all plugins have been transitioned over to use the new-style
 *            namespace-like prefix for hooks, the `WrongPrefix` warning should be
 *            changed to an error and only namespace-like prefixes should be allowed.}
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 *
 * @since   2.0.0
 */
class ValidHookNameSniff extends WPCS_ValidHookNameSniff {

	use CustomPrefixesTrait;

	/**
	 * Maximum number of words.
	 *
	 * The maximum number of words a hook name should consist of, each
	 * separated by an underscore.
	 *
	 * If the name consists of more words, an ERROR will be thrown.
	 *
	 * @var int
	 */
	public $max_words = 4;

	/**
	 * Recommended maximum number of words.
	 *
	 * The recommended maximum number of words a hook name should consist of, each
	 * separated by an underscore.
	 *
	 * If the name consists of more words, a WARNING will be thrown.
	 *
	 * @var int
	 */
	public $recommended_max_words = 4;

	/**
	 * The prefix found (if any).
	 *
	 * @var string
	 */
	private $found_prefix = '';

	/**
	 * Keep track of the content of first text string which was passed to the `transform()`
	 * method as it may be repeatedly called for the same token.
	 *
	 * @var bool
	 */
	private $first_string = '';

	/**
	 * The quote style used for the prefix part of the hook name.
	 *
	 * A double quoted string without variable interpolation will be tokenized as
	 * `T_CONSTANT_ENCAPSED_STRING`, but due to the double quotes, will still need
	 * for namespace separators to be escaped, i.e. double-slashed.
	 *
	 * @var string
	 */
	private $prefix_quote_style = '';

	/**
	 * Process the parameters of a matched function.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched.
	 * @param array  $parameters      Array with information about the parameters.
	 *
	 * @return void
	 */
	public function process_parameters( $stackPtr, $group_name, $matched_content, $parameters ) {
		/*
		 * The custom prefix should be in the first text passed to `transform()` for each
		 * matched function call.
		 *
		 * Reset the properties which help manage this each time a new function call
		 * is encountered.
		 */
		$this->found_prefix       = '';
		$this->first_string       = '';
		$this->prefix_quote_style = '';
		$this->validate_prefixes();

		/*
		 * If any prefixes were passed, check if this is a hook belonging to the plugin being checked.
		 */
		if ( empty( $this->validated_prefixes ) === false ) {
			$param           = $parameters[1];
			$first_non_empty = $this->phpcsFile->findNext( Tokens::$emptyTokens, $param['start'], ( $param['end'] + 1 ), true );
			$found_prefix    = '';

			if ( isset( Tokens::$stringTokens[ $this->tokens[ $first_non_empty ]['code'] ] ) ) {
				$this->prefix_quote_style = $this->tokens[ $first_non_empty ]['content'][0];
				$content                  = \trim( $this->strip_quotes( $this->tokens[ $first_non_empty ]['content'] ) );

				foreach ( $this->validated_prefixes as $prefix ) {
					if ( \strpos( $prefix, '\\' ) === false
						&& \strpos( $content, $prefix ) === 0
					) {
						$found_prefix = $prefix;
						break;
					}

					$prefix = \str_replace( '\\\\', '[\\\\]+', \preg_quote( $prefix, '`' ) );
					if ( \preg_match( '`^' . $prefix . '`', $content, $matches ) === 1 ) {
						$found_prefix = $matches[0];
						break;
					}
				}
			}

			if ( $found_prefix === '' ) {
				/*
				 * Not a hook name with a prefix indicating it belongs to the specific plugin
				 * being checked. Ignore as it's probably a WP Core or external plugin hook name
				 * which we cannot change.
				 */
				return;
			}

			$this->found_prefix = $found_prefix;
		}

		// Do the WPCS native hook name check.
		parent::process_parameters( $stackPtr, $group_name, $matched_content, $parameters );

		if ( $this->found_prefix === '' ) {
			return;
		}

		// Do the YoastCS specific hook name length and prefix check.
		$this->verify_yoast_hook_name( $stackPtr, $parameters );
	}

	/**
	 * Transform an arbitrary string to lowercase and replace punctuation and spaces with underscores.
	 *
	 * This overloads the parent to prevent errors being triggered on the Yoast specific
	 * plugin prefix for hook names and remembers whether a prefix was found to allow
	 * checking whether it was the correct one.
	 *
	 * @param string $string         The target string.
	 * @param string $regex          The punctuation regular expression to use.
	 * @param string $transform_type Whether to do a partial or complete transform.
	 *                               Valid values are: 'full', 'case', 'punctuation'.
	 * @return string
	 */
	protected function transform( $string, $regex, $transform_type = 'full' ) {

		if ( empty( $this->validated_prefixes ) ) {
			return parent::transform( $string, $regex, $transform_type );
		}

		if ( $this->first_string === '' ) {
			$this->first_string = $string;
		}

		// Not the first text string.
		if ( $string !== $this->first_string ) {
			return parent::transform( $string, $regex, $transform_type );
		}

		// Repeated call for the first text string.
		if ( $this->found_prefix !== '' ) {
			$string = \substr( $string, \strlen( $this->found_prefix ) );
		}

		return $this->found_prefix . parent::transform( $string, $regex, $transform_type );
	}

	/**
	 * Additional YoastCS specific hook name checks.
	 *
	 * @param int   $stackPtr   The position of the current token in the stack.
	 * @param array $parameters Array with information about the parameters.
	 *
	 * @return void
	 */
	public function verify_yoast_hook_name( $stackPtr, $parameters ) {

		$param           = $parameters[1];
		$first_non_empty = $this->phpcsFile->findNext( Tokens::$emptyTokens, $param['start'], ( $param['end'] + 1 ), true );

		/*
		 * Check that the namespace-like prefix is used for hooks.
		 */
		if ( \strpos( $this->found_prefix, '\\' ) === false ) {
			/*
			 * Find which namespace-based prefix should have been used.
			 * Loop till the end as the shortest prefix will be last.
			 */
			$namespace_prefix = '';
			foreach ( $this->validated_prefixes as $prefix ) {
				if ( \strpos( $prefix, '\\' ) !== false ) {
					$namespace_prefix = $prefix;
				}
			}

			$this->phpcsFile->addWarning(
				'Wrong prefix type used. Hook names should use the "%s" namespace-like prefix. Found prefix: %s',
				$first_non_empty,
				'WrongPrefix',
				[
					$namespace_prefix,
					$this->found_prefix,
				]
			);

			$this->phpcsFile->recordMetric( $stackPtr, 'Hook name prefix type', 'old_style' );
		}
		else {
			$this->phpcsFile->recordMetric( $stackPtr, 'Hook name prefix type', 'new\style' );

			/*
			 * Check whether the namespace separator slashes are correctly escaped.
			 */
			if ( $this->prefix_quote_style === '"' ) {
				\preg_match_all( '`[\\\\]+`', $this->found_prefix, $matches );
				if ( empty( $matches ) === false ) {
					$counter = 0;
					foreach ( $matches[0] as $match ) {
						if ( $match === '\\' ) {
							++$counter;
						}
					}

					if ( $counter > 0 ) {
						$this->phpcsFile->addWarning(
							'When using a double quoted string for the hook name, it is strongly recommended to escape the backslashes in the hook name (prefix). Found %s unescaped backslashes.',
							$first_non_empty,
							'EscapeSlashes',
							[ $counter ]
						);
					}
				}
			}
		}

		/*
		 * Check if the hook name is a single quoted string.
		 */
		$allow  = [ \T_CONSTANT_ENCAPSED_STRING ];
		$allow += Tokens::$emptyTokens;

		$has_non_string = $this->phpcsFile->findNext( $allow, $param['start'], ( $param['end'] + 1 ), true );
		if ( $has_non_string !== false ) {
			/*
			 * Double quoted string or a hook name concatenated together, checking the word count for the
			 * hook name can not be done in a reliable manner.
			 *
			 * Throwing a warning to allow for examining these if desired.
			 * Severity 3 makes sure that this warning will normally be invisible and will only
			 * be thrown when PHPCS is explicitly requested to check with a lower severity.
			 */
			$this->phpcsFile->addWarning(
				'Hook name could not reliably be examined for maximum word count. Please verify this hook name manually. Found: %s',
				$first_non_empty,
				'NonString',
				[ $param['raw'] ],
				3
			);

			$this->phpcsFile->recordMetric( $stackPtr, 'Nr of words in hook name', 'undetermined' );

			return;
		}

		/*
		 * Check the hook name depth.
		 */
		$hook_ptr  = $first_non_empty; // If no other tokens were found, the first non empty will be the hook name.
		$hook_name = $this->strip_quotes( $this->tokens[ $hook_ptr ]['content'] );
		$hook_name = \substr( $hook_name, \strlen( $this->found_prefix ) );

		$parts      = \explode( '_', $hook_name );
		$part_count = \count( $parts );

		$this->phpcsFile->recordMetric( $stackPtr, 'Nr of words in hook name', $part_count );

		if ( $part_count <= $this->recommended_max_words && $part_count <= $this->max_words ) {
			return;
		}

		if ( $part_count > $this->max_words ) {
			$error = 'A hook name is not allowed to consist of more than %d words after the plugin prefix. Words found: %d in %s';
			$data  = [
				$this->max_words,
				$part_count,
				$this->tokens[ $hook_ptr ]['content'],
			];

			$this->phpcsFile->addError( $error, $hook_ptr, 'MaxExceeded', $data );
		}
		elseif ( $part_count > $this->recommended_max_words ) {
			$error = 'A hook name should not consist of more than %d words after the plugin prefix. Words found: %d in %s';
			$data  = [
				$this->recommended_max_words,
				$part_count,
				$this->tokens[ $hook_ptr ]['content'],
			];

			$this->phpcsFile->addWarning( $error, $hook_ptr, 'TooLong', $data );
		}
	}
}
