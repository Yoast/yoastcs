<?php

namespace YoastCS\Yoast\Tests\NamingConventions;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the ValidHookName sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   2.0.0
 *
 * @covers  YoastCS\Yoast\Sniffs\NamingConventions\ValidHookNameSniff
 * @covers  YoastCS\Yoast\Utils\CustomPrefixesTrait
 */
class ValidHookNameUnitTest extends AbstractSniffUnitTest {

	/**
	 * Set warnings level to 3 to trigger suggestions as warnings.
	 *
	 * @param string $filename The name of the file being tested.
	 * @param Config $config   The config data for the run.
	 *
	 * @return void
	 */
	public function setCliValues( $filename, $config ) {
		$config->warningSeverity = 3;
	}

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {

		return [
			14  => 1,
			15  => 1,
			17  => 1,
			18  => 1,
			19  => 1,
			34  => 1,
			35  => 1,
			42  => 1,
			83  => 1,
			84  => 1,
			96  => 1,
			97  => 1,
			108 => 1,
			111 => 1,
			119 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [
			16  => 1,
			19  => 1,
			33  => 1,
			35  => 1,
			42  => 2,
			45  => 1, // Severity: 3.
			48  => 1, // Severity: 3.
			72  => 1, // Severity: 3.
			73  => 2, // Severity: 3 + 5.
			81  => 1,
			84  => 1,
			94  => 1,
			97  => 1,
			107 => 1,
			110 => 2,
			111 => 1,
		];
	}
}

