<?php

namespace YoastCS\Yoast\Tests\NamingConventions;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the ObjectNameDepth sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   1.4.0
 *
 * @covers  YoastCS\Yoast\Sniffs\NamingConventions\ObjectNameDepthSniff
 */
class ObjectNameDepthUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList( $testFile = '' ) {

		switch ( $testFile ) {
			case 'ObjectNameDepthUnitTest.2.inc':
				return [
					21 => 1,
					22 => 1,
					23 => 1,
					33 => 1,
					42 => 1,
					43 => 1,
					58 => 1,
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
	public function getWarningList( $testFile = '' ) {
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

