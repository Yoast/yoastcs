<?php

namespace YoastCS\Yoast\Tests\Commenting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the CoversTag sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   1.3.0
 *
 * @covers  YoastCS\Yoast\Sniffs\Commenting\CoversTagSniff
 */
class CoversTagUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			34  => 1,
			35  => 1,
			36  => 1,
			37  => 1,
			38  => 1,
			39  => 1,
			40  => 1,
			47  => 1,
			48  => 1,
			49  => 1,
			50  => 2,
			57  => 1,
			58  => 1,
			59  => 1,
			66  => 1,
			67  => 1,
			68  => 1,
			75  => 1,
			76  => 1,
			77  => 1,
			84  => 1,
			91  => 1,
			101 => 1,
			114 => 1,
			127 => 1,
			140 => 1,
			150 => 1,
			151 => 1,
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
