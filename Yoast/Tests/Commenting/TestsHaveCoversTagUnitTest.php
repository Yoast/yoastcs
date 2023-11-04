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
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList( string $testFile = '' ): array {

		switch ( $testFile ) {
			case 'TestsHaveCoversTagUnitTest.1.inc':
				return [
					49  => 1,
					59  => 1,
					88  => 1,
					126 => 1,
					142 => 1,
					150 => 1,
					173 => 1,
				];

			default:
				return [];
		}
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList(): array {
		return [];
	}
}
