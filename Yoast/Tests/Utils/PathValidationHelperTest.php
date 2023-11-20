<?php

namespace YoastCS\Yoast\Tests\Utils;

use YoastCS\Yoast\Tests\NonSniffTestCase;
use YoastCS\Yoast\Utils\PathValidationHelper;

/**
 * Tests for the YoastCS\Yoast\Utils\PathValidationHelper class.
 *
 * @coversDefaultClass \YoastCS\Yoast\Utils\PathValidationHelper
 *
 * @since 3.0.0
 */
final class PathValidationHelperTest extends NonSniffTestCase {

	/**
	 * Default basepath.
	 *
	 * @var string
	 */
	private const DIRTY_BASEPATH = '/base/path';

	/**
	 * Cleaned up version of the default basepath.
	 *
	 * @var string
	 */
	private const CLEAN_BASEPATH = '/base/path/';

	/**
	 * Test converting a set of relative paths to absolute paths when no basepath is present.
	 *
	 * @covers ::relative_to_absolute
	 *
	 * @return void
	 */
	public function test_relative_to_absolute_no_basepath() {
		$phpcsFile = $this->get_mock_file();

		$this->assertSame( [], PathValidationHelper::relative_to_absolute( $phpcsFile, [ 'path' ] ) );
	}

	/**
	 * Test converting a set of relative paths to absolute paths when no paths where passed.
	 *
	 * @dataProvider data_relative_to_absolute_no_paths
	 * @covers       ::relative_to_absolute
	 *
	 * @param array<string> $input The input array.
	 *
	 * @return void
	 */
	public function test_relative_to_absolute_no_paths( $input ) {
		$phpcsFile                   = $this->get_mock_file();
		$phpcsFile->config->basepath = self::DIRTY_BASEPATH;

		$this->assertSame( [], PathValidationHelper::relative_to_absolute( $phpcsFile, $input ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_relative_to_absolute_no_paths() For the array format.
	 *
	 * @return array<string, array<string, array<string>>>
	 */
	public static function data_relative_to_absolute_no_paths() {
		return [
			'empty array' => [
				'input' => [],
			],
			'array with only empty values' => [
				'input' => [
					'',
					' ',
					'   ',
				],
			],
		];
	}

	/**
	 * Test converting a set of relative paths to absolute paths.
	 *
	 * @dataProvider data_relative_to_absolute
	 * @covers       ::relative_to_absolute
	 *
	 * @param array<string> $input    The input array.
	 * @param array<string> $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_relative_to_absolute( $input, $expected ) {
		$phpcsFile                   = $this->get_mock_file();
		$phpcsFile->config->basepath = self::DIRTY_BASEPATH;

		$this->assertSame( $expected, PathValidationHelper::relative_to_absolute( $phpcsFile, $input ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_relative_to_absolute() For the array format.
	 *
	 * @return array<string, array<string, array<string>>>
	 */
	public static function data_relative_to_absolute() {
		return [
			'all cases' => [
				'input' => [
					'../walking/up/',
					'/walking/../up/',
					'/walking/up/../',
					' . ',
					'.',
					'./',
					'.\\',
					'./some/path',
					'./some/path/to/file.ext',
					'/some/path',
					'/some/path/to/file.ext',
					'some/path',
					'some/path/to/file.ext',
					'\some\path',
					'\some\path\to\file.ext',
					'.\some\path',
					'.\some\path\to\file.ext',
				],
				'expected' => [
					' . '                     => self::CLEAN_BASEPATH,
					'.'                       => self::CLEAN_BASEPATH,
					'./'                      => self::CLEAN_BASEPATH,
					'.\\'                     => self::CLEAN_BASEPATH,
					'./some/path'             => self::CLEAN_BASEPATH . 'some/path/',
					'./some/path/to/file.ext' => self::CLEAN_BASEPATH . 'some/path/to/file.ext',
					'/some/path'              => self::CLEAN_BASEPATH . 'some/path/',
					'/some/path/to/file.ext'  => self::CLEAN_BASEPATH . 'some/path/to/file.ext',
					'some/path'               => self::CLEAN_BASEPATH . 'some/path/',
					'some/path/to/file.ext'   => self::CLEAN_BASEPATH . 'some/path/to/file.ext',
					'\some\path'              => self::CLEAN_BASEPATH . 'some/path/',
					'\some\path\to\file.ext'  => self::CLEAN_BASEPATH . 'some/path/to/file.ext',
					'.\some\path'             => self::CLEAN_BASEPATH . 'some/path/',
					'.\some\path\to\file.ext' => self::CLEAN_BASEPATH . 'some/path/to/file.ext',
				],
			],
		];
	}
}
