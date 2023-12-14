<?php

namespace YoastCS\Yoast\Tests\WhiteSpace;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FunctionSpacing sniff.
 *
 * @since 1.0.0
 *
 * @covers YoastCS\Yoast\Sniffs\WhiteSpace\FunctionSpacingSniff
 */
final class FunctionSpacingUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(): array {
		return [
			31  => 1,
			33  => 1,
			39  => 1,
			46  => 1,
			53  => 2,
			57  => 1,
			60  => 1,
			74  => 1,
			87  => 2,
			88  => 1,
			94  => 1,
			96  => 1,
			102 => 1,
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
