<?php

namespace YoastCS\Yoast\Tests\Files;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FileName sniff.
 *
 * @since 0.5
 *
 * @covers YoastCS\Yoast\Sniffs\Files\FileNameSniff
 */
final class FileNameUnitTest extends AbstractSniffUnitTest {

	/**
	 * Error files with the expected nr of errors.
	 *
	 * @var array<string, int>
	 */
	private $expected_results = [

		/*
		 * In /FileNameUnitTests.
		 */

		// Live coding/parse error test.
		'live-coding.inc'                 => 0,

		// Exclusions.
		'excluded-file.inc'               => 0,
		'ExcludedFile.inc'                => 1, // Lowercase expected.
		'excluded_file.inc'               => 1, // Dashes, not underscores.

		// File names generic.
		'some_file.inc'                   => 1, // Dashes, not underscores.
		'SomeFile.inc'                    => 1, // Lowercase expected.
		'some-File.inc'                   => 1, // Lowercase expected.
		'short-open.inc'                  => 0,
		'ShortOpen.inc'                   => 1, // Lowercase expected.
		'short_Open.inc'                  => 1, // Lowercase expected, dashes, not underscores.
		'dot.not.underscore.inc'          => 1, // Dashes, not other punctuation.
		'with#other+punctuation.inc'      => 1, // Dashes, not other punctuation.

		// Class file names.
		'my-class.inc'                    => 0,
		'My_Class.inc'                    => 1, // Lowercase expected, dashes, not underscores.
		'different-class.inc'             => 1, // Filename not in line with class name.
		'class-my-class.inc'              => 1, // Prefix 'class' not needed.
		'some-class.inc'                  => 0,
		'wpseo-some-class.inc'            => 1, // Prefix 'wpseo' not necessary.
		'yoast-plugin-some-class.inc'     => 1, // Prefix 'yoast-plugin' not necessary.
		'yoast.inc'                       => 0, // Class name = prefix, so there would be nothing left otherwise.
		'class-wpseo-some-class.inc'      => 1, // Prefixes 'class' and 'wpseo' not necessary.
		'excluded-CLASS-file.inc'         => 1, // Lowercase expected.
		'excluded-backslash-file.inc'     => 0,
		'excluded-class-wrong-case.inc'   => 1, // Filename not in line with class name. File not excluded due to wrong case used.
		'excluded-illegal.inc'            => 1, // Filename not in line with class name. File not excluded due to illegal path setting.
		'excluded-multiple.inc'           => 0,
		'excluded-dot-prefixed.inc'       => 0,

		// Interface file names.
		'outline-something-interface.inc' => 0,
		'different-interface.inc'         => 1, // Filename not in line with interface name.
		'outline-something.inc'           => 1, // Missing '-interface' suffix.
		'yoast-outline-something.inc'     => 1, // Prefix 'yoast' not needed.
		'no-duplicate-interface.inc'      => 0, // Check that 'Interface' in interface name does not cause duplication in filename.
		'excluded-interface-file.inc'     => 0,

		// Trait file names.
		'outline-mything-trait.inc'       => 0,
		'different-trait.inc'             => 1, // Filename not in line with trait name.
		'outline-mything.inc'             => 1, // Missing '-trait' suffix.
		'yoast-outline-mything.inc'       => 1, // Prefix 'yoast' not needed.
		'no-duplicate-trait.inc'          => 0, // Check that 'Trait' in trait name does not cause duplication in filename.
		'excluded-trait-file.inc'         => 0,

		// Enum file names.
		'something-enum.inc'              => 0,
		'different-enum.inc'              => 1, // Filename not in line with enum name.
		'something.inc'                   => 1, // Missing '-enum' suffix.
		'yoast-something.inc'             => 1, // Prefix 'yoast' not needed.
		'no-duplicate-enum.inc'           => 0, // Check that 'Enum' in enum name does not cause duplication in filename.
		'excluded-enum.inc'               => 0,

		// Functions file names.
		'functions.inc'                   => 0,
		'some-functions.inc'              => 0,
		'some-file.inc'                   => 1, // Missing '-functions' suffix.
		'excluded-functions-file.inc'     => 0,

		// Ignore annotation handling.
		'blanket-disable.inc'             => 0,
		'yoast-disable.inc'               => 0,
		'category-disable.inc'            => 0,
		'rule-disable.inc'                => 0,
		'disable-matching-enable.inc'     => 1,
		'disable-non-matching-enable.inc' => 0,
		'non-relevant-disable.inc'        => 1,
		'partial-file-disable.inc'        => 1,
		'Errorcode_Disable.inc'           => 1, // The sniff can only be disabled completely, not by error code.

		// PSR4 file names.
		'Some_Class.inc'                  => 0,
		'Some_Enum.inc'                   => 0,
		'Some_Interface.inc'              => 0,
		'Some_Trait.inc'                  => 0,
		'Wrong_Class.inc'                 => 1, // Filename not in line with class name.
		'Wrong_Enum.inc'                  => 1, // Filename not in line with enum name.
		'Wrong_Interface.inc'             => 1, // Filename not in line with interface name.
		'Wrong_Trait.inc'                 => 1, // Filename not in line with trait name.
		'wrong_case.inc'                  => 1, // Names are case-sensitive.
		'Wrong_Case_Too.inc'              => 1, // Names are case-sensitive.
		'WPSEO_Prefixed.inc'              => 0, // Prefixes should not be stripped for PSR4 file names.
		'Yoast_Prefixed.inc'              => 0, // Prefixes should not be stripped for PSR4 file names.
		'Prefix_Stripped.inc'             => 1, // Prefixes should not be stripped for PSR4 file names.
		'Not_Excluded.inc'                => 1, // Exclusions do not apply to files where prefix stripping is not supported.
		'no-oo.inc'                       => 0, // Files not containing OO should follow the normal rules for PSR4 dirs.
		'no-oo-functions.inc'             => 0, // Files containing only functions should follow the normal rules for PSR4 dirs.
		'missing-suffix.inc'              => 1, // Files containing only functions should follow the normal rules for PSR4 dirs.
		'Multiple_Paths.inc'              => 0,
		'Dot_Prefixed_Path.inc'           => 0,
		'illegal-psr4-path.inc'           => 0, // PSR4 path ignored, so normal rules apply.
		'Illegal_PSR4_Path.inc'           => 1, // PSR4 path ignored, so normal rules apply.

		/*
		 * In /.
		 */

		// Fall-back file in case glob() fails.
		'FileNameUnitTest.inc'            => 1,

		// PSR4 related, PSR4 dir is root dir.
		'not-in-psr4-path.inc'            => 0,
		'not-in-psr4-path-wrong-name.inc' => 1, // Filename not in line with class name, non-PSR4.
		'PSR4_Path_Is_Root_Path.inc'      => 0,
		'PSR4_Path_Root_Wrong_Name.inc'   => 1, // Filename not in line with class name, PSR4.
	];

	/**
	 * Set CLI values before the file is tested.
	 *
	 * @param string $filename The name of the file being tested.
	 * @param Config $config   The config data for the test run.
	 *
	 * @return void
	 */
	public function setCliValues( $filename, $config ): void {
		if ( $filename === 'no-basepath.inc' || $filename === 'no-basepath-psr4.inc' ) {
			return;
		}

		$config->basepath = __DIR__ . \DIRECTORY_SEPARATOR . 'FileNameUnitTests';
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
		$test_files = \glob( \dirname( $testFileBase ) . $sep . 'FileNameUnitTests{' . $sep . ',' . $sep . '*' . $sep . '}*.inc', \GLOB_BRACE );

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

		if ( isset( $this->expected_results[ $testFile ] ) ) {
			return [
				1 => $this->expected_results[ $testFile ],
			];
		}

		return [];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array<int, int> Key is the line number, value is the number of expected warnings.
	 */
	public function getWarningList( string $testFile = '' ): array {
		/*
		 * Note: no warning for the 'no-basepath.inc' file as the warning will only be thrown once.
		 * Also note that in which file the warning is thrown, relies on the sorting order of the
		 * test files, which could change.
		 */
		if ( $testFile === 'no-basepath-psr4.inc' ) {
			return [
				1 => 1,
			];
		}

		return [];
	}
}
