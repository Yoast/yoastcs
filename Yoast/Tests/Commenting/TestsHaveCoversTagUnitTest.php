<?php

namespace YoastCS\Yoast\Tests\Commenting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the TestsHaveCoversTag sniff.
 *
 * @since 1.3.0
 *
 * @covers YoastCS\Yoast\Sniffs\Commenting\TestsHaveCoversTagSniff
 */
final class TestsHaveCoversTagUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList(): array {
		return [
			49  => 1,
			59  => 1,
			88  => 1,
			126 => 1,
			142 => 1,
			150 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList(): array {
		return [];
	}
}
