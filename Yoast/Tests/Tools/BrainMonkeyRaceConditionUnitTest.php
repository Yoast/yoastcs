<?php

namespace YoastCS\Yoast\Tests\Tools;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the BrainMonkeyRaceCondition sniff.
 *
 * @package Yoast\YoastCS
 *
 * @covers  YoastCS\Yoast\Sniffs\Tools\BrainMonkeyRaceConditionSniff
 */
class BrainMonkeyRaceConditionUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			104 => 1,
			113 => 1,
			122 => 1,
			131 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [];
	}
}
