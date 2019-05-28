<?php

namespace YoastCS\Yoast\Tests\WhiteSpace;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FunctionSpacing sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   1.0.0
 *
 * @covers  YoastCS\Yoast\Sniffs\WhiteSpace\FunctionSpacingSniff
 */
class FunctionSpacingUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			31 => 1,
			33 => 1,
			39 => 1,
			46 => 1,
			53 => 2,
			57 => 1,
			60 => 1,
			74 => 1,
			87 => 2,
			88 => 1,
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
