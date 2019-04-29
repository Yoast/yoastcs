<?php

namespace YoastCS\Yoast\Tests\Yoast;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the AlternativeFunctions sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   1.3.0
 */
class AlternativeFunctionsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			12 => 1,
			13 => 1,
			14 => 1,
			16 => 1,
			17 => 1,
			21 => 1,
			22 => 1,
		);
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array();
	}
}
