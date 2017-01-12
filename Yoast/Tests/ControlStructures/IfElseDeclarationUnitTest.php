<?php
/**
 * Unit test class for the Yoast Coding Standard.
 *
 * @package Yoast\YoastCS
 * @license https://opensource.org/licenses/MIT MIT
 */

/**
 * Unit test class for the ControlStructureSpacing sniff.
 *
 * @package Yoast\YoastCS
 * @since   0.5
 */
class Yoast_Tests_ControlStructures_IfElseDeclarationUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			19 => 1,
			25 => 1,
			27 => 1,
			29 => 1,
		);

	} // end getErrorList()

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array();

	}

} // End class.
