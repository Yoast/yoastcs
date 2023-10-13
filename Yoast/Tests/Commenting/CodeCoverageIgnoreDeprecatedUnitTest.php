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
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList(): array {
		return [
			41 => 1,
			50 => 1,
			55 => 1,
			69 => 1,
			90 => 1,
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
