<?php
/**
 * File Comment for a file without a namespace.
 *
 * @package Some\Package
 */

/**
 * Class docblock.
 */
class Testing {
	public function test() {
		echo namespace\SomeClass::$static_property; // This is not a namespace declaration, but use of the namespace operator.
	}
}
