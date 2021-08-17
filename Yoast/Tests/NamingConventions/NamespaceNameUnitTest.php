<?php

namespace YoastCS\Yoast\Tests\NamingConventions;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the NamespaceName sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   2.0.0
 *
 * @covers  YoastCS\Yoast\Sniffs\NamingConventions\NamespaceNameSniff
 * @covers  YoastCS\Yoast\Utils\CustomPrefixesTrait
 */
class NamespaceNameUnitTest extends AbstractSniffUnitTest {

	/**
	 * Set CLI values before the file is tested.
	 *
	 * @param string $testFile The name of the file being tested.
	 * @param Config $config   The config data for the test run.
	 *
	 * @return void
	 */
	public function setCliValues( $testFile, $config ) {
		if ( \strpos( $testFile, 'no-basepath' ) === 0 ) {
			return;
		}

		$config->basepath = __DIR__ . \DIRECTORY_SEPARATOR . 'NamespaceNameUnitTests';
	}

	/**
	 * Get a list of all test files to check.
	 *
	 * @param string $testFileBase The base path that the unit tests files will have.
	 *
	 * @return string[]
	 */
	protected function getTestFiles( $testFileBase ) {
		$sep        = \DIRECTORY_SEPARATOR;
		$test_files = \glob( \dirname( $testFileBase ) . $sep . 'NamespaceNameUnitTests{' . $sep . ',' . $sep . '*' . $sep . '}*.inc', \GLOB_BRACE );

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
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList( $testFile = '' ) {

		switch ( $testFile ) {
			// Level check tests.
			case 'no-basepath.inc':
				return [
					12 => 1,
					21 => 1,
					24 => 1,
					33 => 1,
					44 => 1,
					53 => 1,
					54 => 1,
					66 => 1,
					70 => 1,
					74 => 1,
				];

			case 'no-basepath-scoped.inc':
				return [
					15 => 1,
					24 => 1,
				];

			// Basic path translation tests.
			case 'path-translation-root.inc':
				return [
					11 => 1,
					14 => 1,
				];

			case 'path-translation-sub1.inc':
				return [
					11 => 1,
					12 => 1,
					13 => 1,
				];

			case 'path-translation-sub2.inc':
				return [
					12 => 1,
					13 => 1,
					14 => 1,
					15 => 1,
				];

			// Path translation with $src_directory set tests.
			case 'path-translation-src.inc':
				return [
					12 => 1,
				];

			case 'path-translation-src-sub-a.inc':
				return [
					13 => 1,
				];

			case 'path-translation-src-sub-b.inc':
				return [
					14 => 1,
				];

			// Path translation with multiple items in $src_directory tests.
			case 'path-translation-secondary.inc':
				return [
					13 => 1,
				];

			case 'path-translation-secondary-sub-a.inc':
				return [
					12 => 1,
				];

			// Path translation with multi-level item in $src_directory tests.
			case 'path-translation-ignore-src.inc':
				return [
					12 => 1,
				];

			case 'path-translation-ignore-src-sub-path.inc':
				return [
					12 => 1,
					13 => 1,
					14 => 1,
				];

			// Path translation with no matching $src_directory.
			case 'path-translation-mismatch.inc':
				return [
					13 => 1,
				];

			case 'path-translation-mismatch-illegal.inc':
				return [
					12 => 1,
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
			// Level check tests.
			case 'no-basepath.inc':
				return [
					8  => 1,
					20 => 1,
					23 => 1,
					32 => 1,
					43 => 1,
					65 => 1,
					69 => 1,
					72 => 1,
					73 => 1,
				];

			case 'no-basepath-scoped.inc':
				return [
					13 => 1,
				];

			case 'path-translation-ignore-src-sub-path.inc':
				return [
					14 => 1,
				];

			default:
				return [];
		}
	}
}
