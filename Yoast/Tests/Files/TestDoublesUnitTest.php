<?php
/**
 * Unit test class for Yoast Coding Standard.
 *
 * @package Yoast\YoastCS
 * @author  Juliette Reinders Folmer
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace YoastCS\Yoast\Tests\Files;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the TestDoubles sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   1.0.0
 */
class TestDoublesUnitTest extends AbstractSniffUnitTest {

	/**
	 * Set CLI values before the file is tested.
	 *
	 * @param string                  $testFile The name of the file being tested.
	 * @param \PHP_CodeSniffer\Config $config   The config data for the test run.
	 *
	 * @return void
	 */
	public function setCliValues( $testFile, $config ) {
		if ( $testFile === 'no-basepath.inc' ) {
			return;
		}

		$config->basepath = __DIR__ . DIRECTORY_SEPARATOR . 'TestDoublesUnitTests';
	}

	/**
	 * Get a list of all test files to check.
	 *
	 * @param string $testFileBase The base path that the unit tests files will have.
	 *
	 * @return string[]
	 */
	protected function getTestFiles( $testFileBase ) {
		$sep        = DIRECTORY_SEPARATOR;
		$test_files = glob( dirname( $testFileBase ) . $sep . 'TestDoublesUnitTests{' . $sep . ',' . $sep . '*' . $sep . '}*.inc', GLOB_BRACE );

		if ( ! empty( $test_files ) ) {
			return $test_files;
		}

		return array( $testFileBase . '.inc' );
	}

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList( $testFile = '' ) {

		switch ( $testFile ) {
			// In tests/.
			case 'mock-not-in-correct-dir.inc':
				return array(
					3 => 1,
				);

			case 'multiple-objects-in-file.inc':
				return array(
					5 => 2,
				);

			case 'not-in-correct-custom-dir.inc':
				return array(
					4 => 2,
				);

			case 'not-in-correct-dir-double.inc':
				return array(
					3 => 1,
				);

			case 'not-in-correct-dir-mock.inc':
				return array(
					3 => 1,
				);

			case 'not-double-or-mock.inc': // In tests.
			case 'correct-dir-double.inc': // In tests/doubles.
			case 'correct-dir-mock.inc': // In tests/doubles.
			case 'correct-custom-dir.inc': // In tests/mocks.
			default:
				return array();
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
		if ( $testFile === 'no-basepath.inc' ) {
			return array(
				1 => 1,
			);
		}

		return array();
	}
}
