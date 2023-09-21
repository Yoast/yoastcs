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
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList( string $testFile = '' ): array {
		switch ( $testFile ) {
			case 'FileCommentUnitTest.2.inc':
			case 'FileCommentUnitTest.8.inc':
			case 'FileCommentUnitTest.10.inc':
			case 'FileCommentUnitTest.12.inc':
			case 'FileCommentUnitTest.22.inc':
			case 'FileCommentUnitTest.24.inc':
			case 'FileCommentUnitTest.28.inc':
			case 'FileCommentUnitTest.32.inc':
			case 'FileCommentUnitTest.36.inc':
				return [
					1 => 1,
				];

			case 'FileCommentUnitTest.21.inc':
				return [
					2 => 1,
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
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList( string $testFile = '' ): array {
		switch ( $testFile ) {
			case 'FileCommentUnitTest.4.inc':
			case 'FileCommentUnitTest.6.inc':
			case 'FileCommentUnitTest.14.inc':
			case 'FileCommentUnitTest.20.inc':
			case 'FileCommentUnitTest.26.inc':
			case 'FileCommentUnitTest.30.inc':
			case 'FileCommentUnitTest.34.inc':
				return [
					2 => 1,
				];

			default:
				return [];
		}
	}
}
