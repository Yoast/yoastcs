<?php

namespace YoastCS\Yoast\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHPUnit\Framework\TestCase;

/**
 * Base TestCase for non-sniff, non-code related tests, which still need a PHPCS File object.
 *
 * Note: this class will likely be moved to PHPCSUtils at some point in the future (once PHPCSUtils drops PHP < 7.0).
 *
 * @since 3.0.0
 */
abstract class NonSniffTestCase extends TestCase {

	/**
	 * Create a mocked PHPCS File object.
	 *
	 * @return File
	 */
	protected function get_mock_file() {
		$config = new class() extends Config {

			/**
			 * Disable the original class constructor.
			 */
			public function __construct() {}
		};

		$phpcsFile = new class() extends File {

			/**
			 * Disable the original class constructor.
			 */
			public function __construct() {}
		};

		$phpcsFile->config = $config;

		return $phpcsFile;
	}
}
