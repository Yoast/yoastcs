<?php

namespace YoastCS\Yoast\Tests\Reports;

use YoastCS\Yoast\Reports\Threshold;
use YoastCS\Yoast\Tests\NonSniffTestCase;

/**
 * Tests for the YoastCS\Yoast\Reports\Threshold class.
 *
 * Note: in contrast to the sniff tests, these are not integration tests, but purely functional tests.
 *
 * @coversDefaultClass \YoastCS\Yoast\Reports\Threshold
 *
 * @since 3.0.0
 */
final class ThresholdReportTest extends NonSniffTestCase {

	/**
	 * Pro-forma test for the `generateFileReport()` method.
	 *
	 * @dataProvider data_generate_file_report
	 * @covers       ::generateFileReport
	 *
	 * @param int  $errors   Number for warnings to pass to the function.
	 * @param int  $warnings Number for errors to pass to the function.
	 * @param bool $expected Expected function output.
	 *
	 * @return void
	 */
	public function test_generate_file_report( $errors, $warnings, $expected ) {
		$phpcsFile = $this->get_mock_file();
		$report    = [
			'errors'   => $errors,
			'warnings' => $warnings,
		];

		$threshold_report = new Threshold();

		$this->assertSame( $expected, $threshold_report->generateFileReport( $report, $phpcsFile ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_generate_file_report() For the array format.
	 *
	 * @return array<string, array<string, int|bool>>
	 */
	public static function data_generate_file_report() {
		return [
			'no errors, no warnings' => [
				'errors'   => 0,
				'warnings' => 0,
				'expected' => false,
			],
			'has errors, no warnings' => [
				'errors'   => 10,
				'warnings' => 0,
				'expected' => true,
			],
			'no errors, has warnings' => [
				'errors'   => 0,
				'warnings' => 5,
				'expected' => true,
			],
			'has errors and warnings' => [
				'errors'   => 3,
				'warnings' => 5,
				'expected' => true,
			],
		];
	}

	/**
	 * Test creating the threshold report.
	 *
	 * @dataProvider data_generate
	 * @covers       ::generate
	 *
	 * @param int    $error_threshold   Allowed nr of errors.
	 * @param int    $warning_threshold Allowed nr of warnings.
	 * @param string $expected_output   Expected screen output regex.
	 *
	 * @return void
	 */
	public function test_generate( $error_threshold, $warning_threshold, $expected_output ) {
		// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions
		\putenv( "YOASTCS_THRESHOLD_ERRORS=$error_threshold" );
		\putenv( "YOASTCS_THRESHOLD_WARNINGS=$warning_threshold" );
		// phpcs:enable

		$this->expectOutputRegex( $expected_output );

		$report = new Threshold();
		$report->generate(
			'',
			10,  // Files.
			150, // Errors.
			300, // Warnings.
			10   // Fixable.
		);
	}

	/**
	 * Data provider.
	 *
	 * @see test_generate() For the array format.
	 *
	 * @return array<string, array<string, int|string|bool>>
	 */
	public static function data_generate() {
		return [
			'Threshold: no errors, no warnings allowed' => [
				'error_threshold'   => 0,
				'warning_threshold' => 0,
				'expected_output'   => '`\s+'
					. '\\033\[1mPHP CODE SNIFFER THRESHOLD COMPARISON\\033\[0m\s+'
					. '-{80}\s+'
					. '\\033\[31mCoding standards ERRORS: 150/0\.\\033\[0m\s+'
					. '\\033\[33mCoding standards WARNINGS: 300/0\.\\033\[0m\s+'
					. '\\033\[31mPlease fix any errors introduced in your code and run PHPCS again to verify\.\\033\[0m\s+'
					. '\\033\[33mPlease fix any warnings introduced in your code and run PHPCS again to verify\.\\033\[0m\s+'
					. 'YOASTCS_ABOVE_THRESHOLD: true\s+'
					. 'YOASTCS_THRESHOLD_EXACT_MATCH: false\s+'
					. '`',
			],
			'Threshold: both errors and warnings below threshold' => [
				'error_threshold'   => 160,
				'warning_threshold' => 320,
				'expected_output'   => '`\s+'
					. '\\033\[1mPHP CODE SNIFFER THRESHOLD COMPARISON\\033\[0m\s+'
					. '-{80}\s+'
					. '\\033\[32mCoding standards ERRORS: 150/160\.\\033\[0m\s+'
					. '\\033\[32mCoding standards WARNINGS: 300/320\.\\033\[0m\s+'
					. '\\033\[32mFound less errors than the threshold, great job!\\033\[0m\s+'
					. 'Please update the ERRORS threshold in the composer\.json file to \\033\[32m150\.\\033\[0m\s+'
					. '\\033\[32mFound less warnings than the threshold, great job!\\033\[0m\s+'
					. 'Please update the WARNINGS threshold in the composer.json file to \\033\[32m300\.\\033\[0m\s+'
					. 'Coding standards checks have passed!\s+'
					. 'YOASTCS_ABOVE_THRESHOLD: false\s+'
					. 'YOASTCS_THRESHOLD_EXACT_MATCH: false\s+'
					. '`',
			],
			'Threshold: both errors and warnings exactly at threshold' => [
				'error_threshold'   => 150,
				'warning_threshold' => 300,
				'expected_output'   => '`\s+'
					. '\\033\[1mPHP CODE SNIFFER THRESHOLD COMPARISON\\033\[0m\s+'
					. '-{80}\s+'
					. '\\033\[32mCoding standards ERRORS: 150/150\.\\033\[0m\s+'
					. '\\033\[32mCoding standards WARNINGS: 300/300\.\\033\[0m\s+'
					. 'Coding standards checks have passed!\s+'
					. 'YOASTCS_ABOVE_THRESHOLD: false\s+'
					. 'YOASTCS_THRESHOLD_EXACT_MATCH: true\s+'
					. '`',
			],
			'Threshold: errors below threshold, warnings above' => [
				'error_threshold'   => 155,
				'warning_threshold' => 250,
				'expected_output'   => '`\s+'
					. '\\033\[1mPHP CODE SNIFFER THRESHOLD COMPARISON\\033\[0m\s+'
					. '-{80}\s+'
					. '\\033\[32mCoding standards ERRORS: 150/155\.\\033\[0m\s+'
					. '\\033\[33mCoding standards WARNINGS: 300/250\.\\033\[0m\s+'
					. '\\033\[32mFound less errors than the threshold, great job!\\033\[0m\s+'
					. 'Please update the ERRORS threshold in the composer\.json file to \\033\[32m150\.\\033\[0m\s+'
					. '\\033\[33mPlease fix any warnings introduced in your code and run PHPCS again to verify\.\\033\[0m\s+'
					. 'YOASTCS_ABOVE_THRESHOLD: true\s+'
					. 'YOASTCS_THRESHOLD_EXACT_MATCH: false\s+'
					. '`',
			],
			'Threshold: errors above threshold, warnings below' => [
				'error_threshold'   => 100,
				'warning_threshold' => 500,
				'expected_output'   => '`\s+'
					. '\\033\[1mPHP CODE SNIFFER THRESHOLD COMPARISON\\033\[0m\s+'
					. '-{80}\s+'
					. '\\033\[31mCoding standards ERRORS: 150/100\.\\033\[0m\s+'
					. '\\033\[32mCoding standards WARNINGS: 300/500\.\\033\[0m\s+'
					. '\\033\[31mPlease fix any errors introduced in your code and run PHPCS again to verify\.\\033\[0m\s+'
					. '\\033\[32mFound less warnings than the threshold, great job!\\033\[0m\s+'
					. 'Please update the WARNINGS threshold in the composer.json file to \\033\[32m300\.\\033\[0m\s+'
					. 'YOASTCS_ABOVE_THRESHOLD: true\s+'
					. 'YOASTCS_THRESHOLD_EXACT_MATCH: false\s+'
					. '`',
			],
			'Threshold: both errors and warnings above threshold' => [
				'error_threshold'   => 100,
				'warning_threshold' => 200,
				'expected_output'   => '`\s+'
					. '\\033\[1mPHP CODE SNIFFER THRESHOLD COMPARISON\\033\[0m\s+'
					. '-{80}\s+'
					. '\\033\[31mCoding standards ERRORS: 150/100\.\\033\[0m\s+'
					. '\\033\[33mCoding standards WARNINGS: 300/200\.\\033\[0m\s+'
					. '\\033\[31mPlease fix any errors introduced in your code and run PHPCS again to verify\.\\033\[0m\s+'
					. '\\033\[33mPlease fix any warnings introduced in your code and run PHPCS again to verify\.\\033\[0m\s+'
					. 'YOASTCS_ABOVE_THRESHOLD: true\s+'
					. 'YOASTCS_THRESHOLD_EXACT_MATCH: false\s+'
					. '`',
			],
		];
	}
}
