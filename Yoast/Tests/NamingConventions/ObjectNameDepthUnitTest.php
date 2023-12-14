<?php

namespace YoastCS\Yoast\Tests\NamingConventions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the ObjectNameDepth sniff.
 *
 * @since 2.0.0
 *
 * @covers YoastCS\Yoast\Sniffs\NamingConventions\ObjectNameDepthSniff
 */
final class ObjectNameDepthUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList( string $testFile = '' ): array {

		switch ( $testFile ) {
			case 'ObjectNameDepthUnitTest.2.inc':
				return [
					21  => 1,
					22  => 1,
					23  => 1,
					33  => 1,
					42  => 1,
					43  => 1,
					58  => 1,
					73  => 1,
					87  => 1,
					92  => 1,
					95  => 1,
					96  => 1,
					105 => 1,
					112 => 1, // False positive due to acronym.
					114 => 1,
					115 => 1,
					116 => 1,
					135 => 1,
					136 => 1,
					145 => 1,
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
			case 'ObjectNameDepthUnitTest.2.inc':
				return [
					32 => 1,
				];

			default:
				return [];
		}
	}
}
