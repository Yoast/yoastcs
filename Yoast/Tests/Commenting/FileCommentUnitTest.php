<?php

namespace YoastCS\Yoast\Tests\Commenting;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FileComment sniff.
 *
 * @since 1.2.0
 *
 * @covers YoastCS\Yoast\Sniffs\Commenting\FileCommentSniff
 */
final class FileCommentUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList( string $testFile = '' ): array {
		switch ( $testFile ) {
			case 'FileCommentUnitTest.2.inc':
			case 'FileCommentUnitTest.8.inc':
			case 'FileCommentUnitTest.10.inc':
			case 'FileCommentUnitTest.12.inc':
				return [
					1 => 1,
				];

			default:
				return [];
		}
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList( string $testFile = '' ): array {
		switch ( $testFile ) {
			case 'FileCommentUnitTest.4.inc':
			case 'FileCommentUnitTest.6.inc':
			case 'FileCommentUnitTest.14.inc':
			case 'FileCommentUnitTest.20.inc':
				return [
					2 => 1,
				];

			default:
				return [];
		}
	}
}
