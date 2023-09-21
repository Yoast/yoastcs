<?php
/**
 * File Comment for a file without a namespace and without OO structure.
 *
 * For the purposes of the unit test, the docblock here needs to comply with the
 * complete Squiz file comment rules as the ruleset is not taken into account
 * when unit testing sniffs.
 *
 * @package    Some\Package
 * @subpackage Something\Else
 * @author     Squiz Pty Ltd <products@squiz.net>
 * @copyright  2018 Squiz Pty Ltd (ABN 77 084 670 600)
 */

do_something();

/**
 * Function docblock.
 */
function test() {
	echo namespace\SomeClass::$static_property; // This is not a namespace declaration, but use of the namespace operator.
}
