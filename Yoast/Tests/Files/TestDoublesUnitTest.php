<?php

namespace YoastCS\Yoast\Tests\Files;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the TestDoubles sniff.
 *
 * @since 1.0.0
 *
 * @covers YoastCS\Yoast\Sniffs\Files\TestDoublesSniff
 */
final class TestDoublesUnitTest extends AbstractSniffUnitTest {

	/**
	 * Set CLI values before the file is tested.
	 *
	 * @param string $filename The name of the file being tested.
	 * @param Config $config   The config data for the test run.
	 *
	 * @return void
	 */
	public function setCliValues( $filename, $config ): void {
		if ( $filename === 'no-basepath.inc' ) {
			return;
		}

		$config->basepath = __DIR__ . \DIRECTORY_SEPARATOR . 'TestDoublesUnitTests';
	}

	/**
	 * Get a list of all test files to check.
	 *
	 * @param string $testFileBase The base path that the unit tests files will have.
	 *
	 * @return string[]
	 */
	protected function getTestFiles( $testFileBase ): array {
		$sep        = \DIRECTORY_SEPARATOR;
		$test_files = \glob( \dirname( $testFileBase ) . $sep . 'TestDoublesUnitTests' . $sep . 'tests{' . $sep . ',' . $sep . '*' . $sep . '}*.inc', \GLOB_BRACE );

		if ( ! empty( $test_files ) ) {
			return $test_files;
		}

		return [ $testFileBase . '.inc' ];
	}

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected errors.
	 */
	public function getErrorList( string $testFile = '' ): array {

		switch ( $testFile ) {
			// In tests/.
			case 'mock-not-in-correct-dir.inc':
				return [
					3 => 1,
				];

			case 'non-existant-doubles-dir.inc':
				return [
					4 => 1,
				];

			case 'not-in-correct-custom-dir.inc':
				return [
					4 => 1,
				];

			case 'not-in-correct-dir-double.inc':
				return [
					3 => 1,
				];

			case 'not-in-correct-dir-mock.inc':
				return [
					3 => 1,
				];

			// In tests/doubles.
			case 'correct-dir-not-double-or-mock.inc':
				return [
					3 => 1,
				];

			// In tests/doubles-not-correct.
			case 'not-in-correct-subdir.inc':
				return [
					3 => 1,
				];

			// In tests/mocks.
			case 'correct-custom-dir-not-mock.inc':
				return [
					4 => 1,
				];

			case 'not-double-or-mock.inc': // In tests.
			case 'correct-dir-double.inc': // In tests/doubles.
			case 'correct-dir-mock.inc': // In tests/doubles.
			case 'correct-custom-dir.inc': // In tests/mocks.
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
			case 'no-basepath.inc':
				return [
					1 => 1,
				];

			case 'no-doubles-path-property.inc':
				return [
					1 => 1,
				];

			default:
				return [];
		}
	}
}
