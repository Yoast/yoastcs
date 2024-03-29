<?php

namespace YoastCS\Yoast\Tests\Commenting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the CoversTag sniff.
 *
 * @since 1.3.0
 *
 * @covers YoastCS\Yoast\Sniffs\Commenting\CoversTagSniff
 */
final class CoversTagUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(): array {
		return [
			20  => 1,
			21  => 1,
			22  => 1,
			23  => 1,
			24  => 1,
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
			176 => 1,
			177 => 1,
			178 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList(): array {
		return [
			29  => 1,
			30  => 1,
			31  => 1,
			32  => 1,
			33  => 1,
			34  => 1,
			35  => 1,
			36  => 1,
			37  => 1,
			38  => 1,
			39  => 1,
			40  => 1,
			41  => 1,
			42  => 1,
			190 => 1,
			191 => 1,
			192 => 1,
			193 => 1,
			194 => 1,
			195 => 1,
		];
	}
}
