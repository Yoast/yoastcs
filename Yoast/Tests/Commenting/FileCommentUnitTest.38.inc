<?php
/**
 * File Comment for a file WITH a namespace, but NOT containing an OO structure. This should NOT be flagged as unnecessary.
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

namespace Yoast\Plugin\Sub;

use Ab\C;

/**
 * Function docblock.
 */
function test() {}
