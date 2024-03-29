<?php

namespace YoastCS\Yoast\Tests\NamingConventions;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the NamespaceName sniff.
 *
 * @since 2.0.0
 *
 * @covers YoastCS\Yoast\Sniffs\NamingConventions\NamespaceNameSniff
 * @covers YoastCS\Yoast\Utils\CustomPrefixesTrait
 */
final class NamespaceNameUnitTest extends AbstractSniffUnitTest {

	/**
	 * Set CLI values before the file is tested.
	 *
	 * @param string $filename The name of the file being tested.
	 * @param Config $config   The config data for the test run.
	 *
	 * @return void
	 */
	public function setCliValues( $filename, $config ): void {
		if ( \strpos( $filename, 'no-basepath' ) === 0 ) {
			return;
		}

		$config->basepath = __DIR__ . \DIRECTORY_SEPARATOR . 'NamespaceNameUnitTests';
	}

	/**
	 * Get a list of all test files to check.
	 *
	 * @param string $testFileBase The base path that the unit tests files will have.
	 *
	 * @return array<string>
	 */
	protected function getTestFiles( $testFileBase ): array {
		$sep        = \DIRECTORY_SEPARATOR;
		$test_files = \glob(
			\dirname( $testFileBase ) . $sep . 'NamespaceNameUnitTests{'
				. $sep . ','                                  // Files in the "NamespaceNameUnitTests" directory.
				. $sep . '*' . $sep . ','                     // Files in first-level subdirectories.
				. $sep . '*' . $sep . '*' . $sep . ','        // Files in second-level subdirectories.
				. $sep . '*' . $sep . '*' . $sep . '*' . $sep // Files in third-level subdirectories.
			. '}*.inc',
			\GLOB_BRACE
		);

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
			// Level check tests.
			case 'no-basepath.inc':
				return [
					12  => 1,
					21  => 1,
					23  => 1,
					24  => 2,
					33  => 1,
					44  => 1,
					53  => 1,
					54  => 1,
					66  => 1,
					70  => 1,
					74  => 1,
					81  => 1,
					90  => 1,
					91  => 1,
					92  => 1,
					103 => 1,
					104 => 1,
					105 => 1,
				];

			case 'no-basepath-scoped.inc':
				return [
					14 => 1,
					24 => 1,
				];

			// Basic path translation tests.
			case 'path-translation-root.inc':
				return [
					11 => 1,
					14 => 2,
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

			// Path translation with unconventional chars in directory name.
			case 'path-translation-sub2-dot.inc':
			case 'path-translation-sub2-underscore.inc':
				return [
					12 => 1,
				];

			case 'path-translation-sub2-space.inc':
				return [
					1 => 1, // Invalid dir error.
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
					15 => 1,
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

			// Path translation with $psr4_paths set tests.
			case 'path-translation-psr4.inc':
				return [
					11 => 2,
					12 => 2,
					21 => 2,
					22 => 1,
					23 => 1,
					33 => 2,
					34 => 2,
					35 => 1,
					36 => 1,
					37 => 1,
					53 => 2,
					54 => 2,
					70 => 2,
					71 => 2,
					72 => 2,
					73 => 2,
				];

			case 'path-translation-psr4-case-sensitive-lower.inc':
				return [
					11 => 1,
				];

			case 'path-translation-psr4-case-sensitive-proper.inc':
				return [
					12 => 3,
					13 => 2,
					14 => 1,
					15 => 1,
					16 => 1,
					26 => 2,
					27 => 2,
					28 => 1,
					29 => 1,
					39 => 3,
					40 => 2,
					41 => 1,
					42 => 1,
					52 => 2,
					53 => 2,
					54 => 3,
					55 => 1,
					56 => 1,
					66 => 2,
					67 => 3,
					68 => 1,
					69 => 1,
				];

			case 'path-translation-src-deeper-than-psr4-sub.inc':
				return [
					13 => 1,
					14 => 1,
					15 => 1,
					16 => 3,
					17 => 3,
				];

			// PSR4 path translation with unconventional chars in directory name.
			case 'path-translation-psr4-dash.inc':
			case 'path-translation-psr4-dot.inc':
			case 'path-translation-psr4-space.inc':
				return [
					1 => 1, // Invalid dir error.
				];

			// Path translation with no matching $src_directory.
			case 'path-translation-mismatch.inc':
				return [
					14 => 1,
					24 => 1,
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
			// Level check tests.
			case 'no-basepath.inc':
				return [
					8   => 1,
					20  => 1,
					23  => 1,
					32  => 1,
					43  => 1,
					65  => 1,
					69  => 1,
					72  => 1,
					80  => 1,
					90  => 1,
					103 => 1,
					122 => 1,
					123 => 1,
					124 => 1,
					125 => 1,
					126 => 1,
					127 => 1,
					128 => 1,
					129 => 1,
					147 => 1,
					148 => 1,
					149 => 1,
					150 => 1,
					151 => 1,
					152 => 1,
					153 => 1,
					154 => 1,
					172 => 1,
					173 => 1,
					174 => 1,
					175 => 1,
					176 => 1,
					177 => 1,
					178 => 1,
					179 => 1,
				];

			case 'no-basepath-scoped.inc':
				return [
					13 => 1,
				];

			case 'path-translation-ignore-src-sub-path.inc':
				return [
					14 => 1,
				];

			case 'path-translation-psr4-case-sensitive-proper.inc':
				return [
					13 => 1,
					27 => 1,
					40 => 1,
					53 => 1,
					66 => 1,
				];

			default:
				return [];
		}
	}
}
