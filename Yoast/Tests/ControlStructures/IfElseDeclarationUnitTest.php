<?php

namespace YoastCS\Yoast\Tests\ControlStructures;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the IfElseDeclaration sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   0.4.1
 * @since   0.5   This class now uses namespaces and is no longer compatible with PHPCS 2.x.
 */
class IfElseDeclarationUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return array(
			22 => 1,
			28 => 1,
			30 => 1,
			34 => 1,
			37 => 1,
			44 => 1,
			51 => 1,
			76 => 1,
			84 => 2,
		);
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
