<?php

namespace YoastCS\Yoast\Tests\Yoast;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the AlternativeFunctions sniff.
 *
 * @since 1.3.0
 *
 * @covers YoastCS\Yoast\Sniffs\Yoast\AlternativeFunctionsSniff
 */
final class AlternativeFunctionsUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(): array {
		return [
			12 => 1,
			13 => 1,
			14 => 1,
			16 => 1,
			17 => 1,
			21 => 1,
			22 => 1,
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
