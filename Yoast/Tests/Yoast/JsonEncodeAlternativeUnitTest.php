<?php

namespace YoastCS\Yoast\Tests\Yoast;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the JsonEncodeAlternative sniff.
 *
 * @since 1.3.0
 *
 * @covers YoastCS\Yoast\Sniffs\Yoast\JsonEncodeAlternativeSniff
 */
final class JsonEncodeAlternativeUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(): array {
		return [
			13 => 1,
			14 => 1,
			15 => 1,
			17 => 1,
			18 => 1,
			22 => 1,
			23 => 1,
			26 => 1,
			27 => 1,
			30 => 1,
			31 => 1,
			35 => 1,
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
