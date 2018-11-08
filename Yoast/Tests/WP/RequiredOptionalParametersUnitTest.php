<?php
/**
 * Unit test class for the Yoast Coding Standard.
 *
 * @package Yoast\YoastCS
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace YoastCS\Yoast\Tests\WP;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the RequiredOptionalParameters sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   1.1.0
 */
class RequiredOptionalParametersUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			10 => 1,
			13 => 1,
			16 => 1,
			22 => 1,
			24 => 1,
		);
	} // end getErrorList()

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * The key of the array should represent the line number and the value
	 * should represent the number of warnings that should occur on that line.
	 *
	 * @return array<int, int>
	 */
	public function getWarningList() {
		return array(
			4  => 1,
		);
	}
}
