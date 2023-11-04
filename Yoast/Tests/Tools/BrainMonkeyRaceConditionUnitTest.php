<?php

namespace YoastCS\Yoast\Tests\Tools;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the BrainMonkeyRaceCondition sniff.
 *
 * @since 2.3.0
 *
 * @covers YoastCS\Yoast\Sniffs\Tools\BrainMonkeyRaceConditionSniff
 */
final class BrainMonkeyRaceConditionUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(): array {
		return [
			104 => 1,
			113 => 1,
			122 => 1,
			131 => 1,
			176 => 1,
			185 => 1,
		];
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
