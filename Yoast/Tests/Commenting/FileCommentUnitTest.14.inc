<?php
/**
 * File Comment for a file WITH a namespace and containing an OO structure. This should be flagged as unnecessary.
 *
 * Note: a namespace _within_ a scoped `declare` statement is a parse error, but that's not the concern of this sniff.
 * Scoped strict_types declare statements are also not allowed.
 *
 * @package Some\Package
 */

declare(strict_types=1) {

	namespace Yoast\Plugin\Sub;
	
	/**
	 * Class docblock.
	 */
	class Testing {}

}
