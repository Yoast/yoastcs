<?php

namespace YoastCS\Yoast\Reports;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Reports\Report;

/**
 * Threshold Report for PHP_CodeSniffer.
 *
 * This report expects the following environment variables to be set
 * to compare the scan results against:
 * - YOASTCS_THRESHOLD_ERRORS
 * - YOASTCS_THRESHOLD_WARNINGS
 *
 * To use this report, call PHPCS with the `--report=YoastCS\Yoast\Reports\Threshold` argument.
 *
 * After the report has run, a global `YOASTCS_ABOVE_THRESHOLD` constant (boolean) will be
 * available which can be used in calling scripts.
 *
 * @since 2.2.0
 */
class Threshold implements Report {

	/**
	 * Generate a partial report for a single processed file.
	 *
	 * Function should return TRUE if it printed or stored data about the file
	 * and FALSE if it ignored the file. Returning TRUE indicates that the file and
	 * its data should be counted in the grand totals.
	 *
	 * @param array $report      Prepared report data.
	 * @param File  $phpcsFile   The file being reported on.
	 * @param bool  $showSources Whether to show the source codes.
	 * @param int   $width       Maximum allowed line width.
	 *
	 * @return bool
	 */
	public function generateFileReport( $report, File $phpcsFile, $showSources = false, $width = 80 ) {
		if ( \PHP_CODESNIFFER_VERBOSITY === 0
			&& $report['errors'] === 0
			&& $report['warnings'] === 0
		) {
			// Nothing to do.
			return false;
		}

		return true;
	}

	/**
	 * Generates a summary of all errors and warnings compares against preset thresholds.
	 *
	 * @param string $cachedData    Any partial report data that was returned from
	 *                              generateFileReport during the run.
	 * @param int    $totalFiles    Total number of files processed during the run.
	 * @param int    $totalErrors   Total number of errors found during the run.
	 * @param int    $totalWarnings Total number of warnings found during the run.
	 * @param int    $totalFixable  Total number of problems that can be fixed.
	 * @param bool   $showSources   Whether to show the source codes.
	 * @param int    $width         Maximum allowed line width.
	 * @param bool   $interactive   Whether PHPCS is being run in interactive mode.
	 * @param bool   $toScreen      Whether the report is being printed to screen.
	 *
	 * @return void
	 */
	public function generate(
		$cachedData,
		$totalFiles,
		$totalErrors,
		$totalWarnings,
		$totalFixable,
		$showSources = false,
		$width = 80,
		$interactive = false,
		$toScreen = true
	) {
		$error_threshold   = (int) \getenv( 'YOASTCS_THRESHOLD_ERRORS' );
		$warning_threshold = (int) \getenv( 'YOASTCS_THRESHOLD_WARNINGS' );

		echo \PHP_EOL . "\033[1m" . 'PHP CODE SNIFFER THRESHOLD COMPARISON' . "\033[0m" . \PHP_EOL;
		echo \str_repeat( '-', $width ) . \PHP_EOL;

		$color = "\033[32m"; // Green.
		if ( $totalErrors > $error_threshold ) {
			$color = "\033[31m"; // Red.
		}
		echo "{$color}Coding standards ERRORS: $totalErrors/$error_threshold.\033[0m" . \PHP_EOL;

		$color = "\033[32m";
		if ( $totalWarnings > $warning_threshold ) {
			$color = "\033[33m"; // Orange.
		}
		echo "{$color}Coding standards WARNINGS: $totalWarnings/$warning_threshold.\033[0m" . \PHP_EOL;
		echo \PHP_EOL;

		$above_threshold = false;

		if ( $totalErrors > $error_threshold ) {
			echo "\033[31mPlease fix any errors introduced in your code and run PHPCS again to verify.\033[0m" . \PHP_EOL;
			$above_threshold = true;
		}
		elseif ( $totalErrors < $error_threshold ) {
			echo "\033[32mFound less errors than the threshold, great job!\033[0m" . \PHP_EOL;
			echo "Please update the ERRORS threshold in the composer.json file to \033[32m$totalErrors\033[0m." . \PHP_EOL;
		}

		if ( $totalWarnings > $warning_threshold ) {
			echo "\033[33mPlease fix any warnings introduced in your code and run PHPCS again to verify.\033[0m" . \PHP_EOL;
			$above_threshold = true;
		}
		elseif ( $totalWarnings < $warning_threshold ) {
			echo "\033[32mFound less warnings than the threshold, great job!\033[0m" . \PHP_EOL;
			echo "Please update the WARNINGS threshold in the composer.json file to \033[32m$totalWarnings\033[0m." . \PHP_EOL;
		}

		if ( $above_threshold === false ) {
			echo \PHP_EOL;
			echo 'Coding standards checks have passed!' . \PHP_EOL;
		}

		// Make the threshold comparison outcome available to the calling script.
		\define( 'YOASTCS_ABOVE_THRESHOLD', $above_threshold );
	}
}
