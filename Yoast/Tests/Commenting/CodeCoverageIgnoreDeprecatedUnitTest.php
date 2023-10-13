<?php

namespace YoastCS\Yoast\Tests\Commenting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the CodeCoverageIgnoreDeprecated sniff.
 *
 * @since 1.1.0
 *
 * @covers YoastCS\Yoast\Sniffs\Commenting\CodeCoverageIgnoreDeprecatedSniff
 */
final class CodeCoverageIgnoreDeprecatedUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList(): array {
		return [
			41  => 1,
			50  => 1,
			55  => 1,
			69  => 1,
			90  => 1,
			98  => 1,
			105 => 1,
			113 => 1,
			120 => 1,
			127 => 1,
			154 => 1,
			158 => 1,
			165 => 1,
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
