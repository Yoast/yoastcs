<?php

namespace YoastCS\Yoast\Tests\Files;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the FileName sniff.
 *
 * @package Yoast\YoastCS
 *
 * @since   0.5
 *
 * @covers  YoastCS\Yoast\Sniffs\Files\FileNameSniff
 */
class FileNameUnitTest extends AbstractSniffUnitTest {

	/**
	 * Error files with the expected nr of errors.
	 *
	 * @var array
	 */
	private $expected_results = array(

		/*
		 * In /FileNameUnitTests.
		 */

		// Exclusions.
		'excluded-file.inc'               => 0,
		'ExcludedFile.inc'                => 1, // Lowercase expected.
		'excluded_file.inc'               => 1, // Dashes, not underscores.

		// File names generic.
		'some_file.inc'                   => 1, // Dashes, not underscores.
		'SomeFile.inc'                    => 1, // Lowercase expected.
		'some-File.inc'                   => 1, // Lowercase expected.

		// Class file names.
		'my-class.inc'                    => 0,
		'My_Class.inc'                    => 1, // Lowercase expected, dashes, not underscores.
		'different-class.inc'             => 1, // Filename not in line with class name.
		'class-my-class.inc'              => 1, // Prefix 'class' not needed.
		'some-class.inc'                  => 0,
		'wpseo-some-class.inc'            => 1, // Prefix 'wpseo' not necessary.
		'class-wpseo-some-class.inc'      => 1, // Prefixes 'class' and 'wpseo' not necessary.
		'excluded-CLASS-file.inc'         => 1, // Lowercase expected.

		// Interface file names.
		'outline-something-interface.inc' => 0,
		'different-interface.inc'         => 1, // Filename not in line with interface name.
		'outline-something.inc'           => 1, // Missing '-interface' suffix.
		'yoast-outline-something.inc'     => 1, // Prefix 'yoast' not needed.
		'no-duplicate-interface.inc'      => 0, // Check that 'Interface' in interface name does not cause duplication in filename.
		'excluded-interface-file.inc'     => 0,

		// Trait file names.
		'outline-something-trait.inc'     => 0,
		'different-trait.inc'             => 1, // Filename not in line with trait name.
		'outline-something.inc'           => 1, // Missing '-trait' suffix.
		'yoast-outline-something.inc'     => 1, // Prefix 'yoast' not needed.
		'no-duplicate-trait.inc'          => 0, // Check that 'Trait' in trait name does not cause duplication in filename.
		'excluded-trait-file.inc'         => 0,

		// Functions file names.
		'some-functions.inc'              => 0,
		'some-file.inc'                   => 1, // Missing '-functions' suffix.
		'excluded-functions-file.inc'     => 0,

		/*
		 * In /.
		 */

		// Fall-back file in case glob() fails.
		'FileNameUnitTest.inc'            => 1,
	);

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

		$config->basepath = __DIR__ . DIRECTORY_SEPARATOR . 'FileNameUnitTests';
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
		$test_files = glob( dirname( $testFileBase ) . $sep . 'FileNameUnitTests{' . $sep . ',' . $sep . '*' . $sep . '}*.inc', GLOB_BRACE );

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

		if ( isset( $this->expected_results[ $testFile ] ) ) {
			return array(
				1 => $this->expected_results[ $testFile ],
			);
		}

		return array();
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
