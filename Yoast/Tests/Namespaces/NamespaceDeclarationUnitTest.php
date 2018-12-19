<?php

namespace YoastCS\Yoast\Tests\Namespaces;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the NamespaceDeclaration sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   1.2.0
 */
class NamespaceDeclarationUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList( $testFile = '' ) {
		switch ( $testFile ) {
			case 'NamespaceDeclarationUnitTest.2.inc':
				return array(
					3  => 1,
					5  => 3,
					7  => 2,
					9  => 3,
					11 => 2,
				);

			default:
				return array();
		}
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return array();
	}
}
