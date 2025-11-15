<?php

namespace YoastCS\Yoast\Tests\Utils;

use PHP_CodeSniffer\Exceptions\RuntimeException;
use YoastCS\Yoast\Tests\NonSniffTestCase;
use YoastCS\Yoast\Utils\PSR4PathsTrait;

/**
 * Tests for the YoastCS\Yoast\Utils\PSR4PathsTrait trait.
 *
 * @coversDefaultClass \YoastCS\Yoast\Utils\PSR4PathsTrait
 *
 * @since 3.0.0
 */
final class PSR4PathsTraitTest extends NonSniffTestCase {

	use PSR4PathsTrait;

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
	 * Clean up the trait after each test.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		$this->psr4_paths           = [];
		$this->previous_psr4_paths  = [];
		$this->validated_psr4_paths = [];
	}

	/**
	 * Test validating the $psr4_paths property
	 *
	 * @dataProvider data_is_get_psr4_info
	 * @covers       ::is_in_psr4_path
	 *
	 * @param array<string, string>      $psr4_paths The initial input for the $psr4_paths property.
	 * @param string                     $file_path  The file path to evaluate.
	 * @param array<string, string|bool> $expected   The expected function output.
	 *
	 * @return void
	 */
	public function test_is_in_psr4_path( $psr4_paths, $file_path, $expected ) {
		$phpcsFile                   = $this->get_mock_file();
		$phpcsFile->config->basepath = self::CLEAN_BASEPATH;
		$phpcsFile->path             = $file_path;

		$this->psr4_paths = $psr4_paths;

		$this->assertSame( $expected['is'], $this->is_in_psr4_path( $phpcsFile ) );
	}

	/**
	 * Test validating the $psr4_paths property
	 *
	 * @dataProvider data_is_get_psr4_info
	 * @covers       ::get_psr4_info
	 *
	 * @param array<string, string>      $psr4_paths The initial input for the $psr4_paths property.
	 * @param string                     $file_path  The file path to evaluate.
	 * @param array<string, string|bool> $expected   The expected function output.
	 *
	 * @return void
	 */
	public function test_get_psr4_info( $psr4_paths, $file_path, $expected ) {
		$phpcsFile                   = $this->get_mock_file();
		$phpcsFile->config->basepath = self::DIRTY_BASEPATH;
		$phpcsFile->path             = $file_path;

		$this->psr4_paths = $psr4_paths;

		$this->assertSame( $expected['get'], $this->get_psr4_info( $phpcsFile ) );
	}

	/**
	 * Test validating the $psr4_paths property
	 *
	 * @dataProvider data_is_get_psr4_info
	 * @covers       ::get_psr4_info
	 *
	 * @param array<string, string>      $psr4_paths The initial input for the $psr4_paths property.
	 * @param string                     $file_path  The file path to evaluate.
	 * @param array<string, string|bool> $expected   The expected function output.
	 *
	 * @return void
	 */
	public function test_get_psr4_info_explicit( $psr4_paths, $file_path, $expected ) {
		$phpcsFile                   = $this->get_mock_file();
		$phpcsFile->config->basepath = self::DIRTY_BASEPATH;

		$this->psr4_paths = $psr4_paths;

		$this->assertSame( $expected['get'], $this->get_psr4_info( $phpcsFile, $file_path ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_is_in_psr4_path()        For the array format.
	 * @see test_get_psr4_info()          For the array format.
	 * @see test_get_psr4_info_explicit() For the array format.
	 *
	 * @return array<string, array<string, string|array<string, string|bool|array<string, string>>>>
	 */
	public static function data_is_get_psr4_info() {
		$default_psr4_paths = self::data_validate_psr4_paths()['multiple prefixes, variation of paths']['psr4_paths'];

		return [
			'path is unknown' => [
				'psr4_paths' => [],
				'file_path'  => '',
				'expected'   => [
					'is'  => false,
					'get' => false,
				],
			],
			'path is STDIN' => [
				'psr4_paths' => [],
				'file_path'  => 'STDIN',
				'expected'   => [
					'is'  => false,
					'get' => false,
				],
			],
			'path is single-quoted STDIN' => [
				'psr4_paths' => [],
				'file_path'  => "'STDIN'",
				'expected'   => [
					'is'  => false,
					'get' => false,
				],
			],
			'path is double-quoted STDIN' => [
				'psr4_paths' => [],
				'file_path'  => '"STDIN"',
				'expected'   => [
					'is'  => false,
					'get' => false,
				],
			],
			'PSR-4 paths is empty/not set' => [
				'psr4_paths' => [],
				'file_path'  => 'path/is/not/relevant',
				'expected'   => [
					'is'  => false,
					'get' => false,
				],
			],
			'Linux style file path, not matching' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => 'path/will/not/match/filename.ext',
				'expected'   => [
					'is'  => false,
					'get' => false,
				],
			],
			'Linux style file path, matching' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => self::DIRTY_BASEPATH . '/config/subdir/filename.ext',
				'expected'   => [
					'is'  => true,
					'get' => [
						'prefix'   => 'Plugin\PrefixB',
						'basepath' => self::CLEAN_BASEPATH . 'config/',
						'relative' => 'subdir',
					],
				],
			],
			'Windows style file path, not matching' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => 'path\will\not\match\filename.ext',
				'expected'   => [
					'is'  => false,
					'get' => false,
				],
			],
			'Windows style file path, matching' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => '\base\path\config\subdir\filename.ext',
				'expected'   => [
					'is'  => true,
					'get' => [
						'prefix'   => 'Plugin\PrefixB',
						'basepath' => self::CLEAN_BASEPATH . 'config/',
						'relative' => 'subdir',
					],
				],
			],
			'Mixed slashes in file path, not matching' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => self::DIRTY_BASEPATH . '\subdir\filename.ext',
				'expected'   => [
					'is'  => false,
					'get' => false,
				],
			],
			'Mixed slashes in file path, matching' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => self::CLEAN_BASEPATH . 'subA\sub/deeper/filename.ext',
				'expected'   => [
					'is'  => true,
					'get' => [
						'prefix'   => 'Plugin\PrefixC',
						'basepath' => self::CLEAN_BASEPATH . 'subA/sub/',
						'relative' => 'deeper',
					],
				],
			],
			'Exact match, no file name' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => self::CLEAN_BASEPATH . 'tests/',
				'expected'   => [
					'is'  => true,
					'get' => [
						'prefix'   => 'Plugin\PrefixB',
						'basepath' => self::CLEAN_BASEPATH . 'tests/',
						'relative' => '.',
					],
				],
			],
			'Exact match, no file name, no trailing slash' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => self::CLEAN_BASEPATH . 'subC',
				'expected'   => [
					'is'  => true,
					'get' => [
						'prefix'   => 'Plugin\PrefixC',
						'basepath' => self::CLEAN_BASEPATH . 'subC/',
						'relative' => '.',
					],
				],
			],
			'Exact match, with file name' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => self::CLEAN_BASEPATH . 'subC/file.ext',
				'expected'   => [
					'is'  => true,
					'get' => [
						'prefix'   => 'Plugin\PrefixC',
						'basepath' => self::CLEAN_BASEPATH . 'subC/',
						'relative' => '.',
					],
				],
			],
			'Long subdir, matching' => [
				'psr4_paths' => $default_psr4_paths,
				'file_path'  => self::CLEAN_BASEPATH . 'src/something/else/and/more/file.ext',
				'expected'   => [
					'is'  => true,
					'get' => [
						'prefix'   => 'Plugin\PrefixA',
						'basepath' => self::CLEAN_BASEPATH . 'src/',
						'relative' => 'something/else/and/more',
					],
				],
			],
		];
	}

	/**
	 * Test validating the $psr4_paths property when no basepath is present.
	 *
	 * @covers ::validate_psr4_paths
	 *
	 * @return void
	 */
	public function test_validate_psr4_paths_without_basepath_doesnt_set_validated() {
		$phpcsFile = $this->get_mock_file();

		$this->psr4_paths = self::data_validate_psr4_paths()['multiple prefixes, variation of paths']['psr4_paths'];

		$this->validate_psr4_paths( $phpcsFile );

		$this->assertSame( [], $this->previous_psr4_paths, 'Previous paths has been set' );
		$this->assertSame( [], $this->validated_psr4_paths, 'Validated paths has been set' );
	}

	/**
	 * Test validating the $psr4_paths property when no basepath is present while a previous run did have a basepath.
	 *
	 * @covers ::validate_psr4_paths
	 *
	 * @return void
	 */
	public function test_validate_psr4_paths_without_basepath_resets_validated() {
		$phpcsFile = $this->get_mock_file();

		// Initial test with basepath.
		$phpcsFile->config->basepath = self::DIRTY_BASEPATH;

		$input            = self::data_validate_psr4_paths()['multiple prefixes, variation of paths'];
		$this->psr4_paths = $input['psr4_paths'];

		$this->validate_psr4_paths( $phpcsFile );

		$this->assertSame( $input['psr4_paths'], $this->previous_psr4_paths, 'Previous paths has not been set correctly' );
		$this->assertSame( $input['expected'], $this->validated_psr4_paths, 'Validated paths has not been set correctly' );

		// Now make sure that the missing basepath resets the validated paths.
		$phpcsFile->config->basepath = null;

		$this->validate_psr4_paths( $phpcsFile );

		$this->assertSame( $input['psr4_paths'], $this->previous_psr4_paths, 'Previous paths is not still the same' );
		$this->assertSame( [], $this->validated_psr4_paths, 'Validated paths has not been reset' );
	}

	/**
	 * Test validating the $psr4_paths property results in an exception when no prefixes are passed.
	 *
	 * @covers ::validate_psr4_paths
	 *
	 * @return void
	 */
	public function test_validate_psr4_paths_throws_exception_on_missing_prefixes() {
		$phpcsFile                   = $this->get_mock_file();
		$phpcsFile->config->basepath = self::CLEAN_BASEPATH;

		// PSR-4 paths contains the same path for two different prefixes.
		$this->psr4_paths = [ // @phpstan-ignore assign.propertyType (This is exactly what we're testing)
			'src',
			'tests',
		];

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage(
			'Invalid value passed for `psr4_paths`. Path "src" is not associated with a namespace prefix'
		);

		$this->validate_psr4_paths( $phpcsFile );
	}

	/**
	 * Test validating the $psr4_paths property results in an exception when the same path is passed for multiple prefixes.
	 *
	 * @covers ::validate_psr4_paths
	 *
	 * @return void
	 */
	public function test_validate_psr4_paths_throws_exception_on_duplicate_paths_for_different_prefixes() {
		$phpcsFile                   = $this->get_mock_file();
		$phpcsFile->config->basepath = self::DIRTY_BASEPATH;

		// PSR-4 paths contains the same path for two different prefixes.
		$this->psr4_paths = [
			'Plugin\Prefix\\'       => 'src/',
			'Plugin\Prefix\Tests\\' => 'src/,tests/',
		];

		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage(
			'Invalid value passed for `psr4_paths`. Multiple prefixes include the same path. Problem path: ' . self::CLEAN_BASEPATH . 'src/'
		);

		$this->validate_psr4_paths( $phpcsFile );
	}

	/**
	 * Test validating the $psr4_paths property
	 *
	 * @dataProvider data_validate_psr4_paths
	 * @covers       ::validate_psr4_paths
	 *
	 * @param array<string, string> $psr4_paths The initial input for the $psr4_paths property.
	 * @param array<string, string> $expected   The expected value for the validated property.
	 *
	 * @return void
	 */
	public function test_validate_psr4_paths( $psr4_paths, $expected ) {
		$phpcsFile                   = $this->get_mock_file();
		$phpcsFile->config->basepath = self::CLEAN_BASEPATH;

		$this->psr4_paths = $psr4_paths;

		$this->validate_psr4_paths( $phpcsFile );

		$this->assertSame( $psr4_paths, $this->previous_psr4_paths );
		$this->assertSame( $expected, $this->validated_psr4_paths );
	}

	/**
	 * Data provider.
	 *
	 * @see test_validate_psr4_paths() For the array format.
	 *
	 * @return array<string, array<string, array<string, string>>>
	 */
	public static function data_validate_psr4_paths() {
		return [
			'empty array' => [
				'psr4_paths' => [],
				'expected'   => [],
			],
			'array with only empty values' => [
				'psr4_paths' => [
					'Plugin\PrefixA' => '',
					'Plugin\PrefixB' => ' ',
					'Plugin\PrefixC' => '   ',
					'Plugin\PrefixD' => '   ,, "   ", \' \' ',
				],
				'expected'   => [],
			],

			'single prefix, single path; no trailing slash at end of prefix' => [
				'psr4_paths' => [
					'Plugin\Prefix' => 'src',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/' => 'Plugin\Prefix',
				],
			],
			'single prefix, single path; trailing slash at end of prefix' => [
				'psr4_paths' => [
					'Plugin\Prefix\\' => 'src',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/' => 'Plugin\Prefix',
				],
			],
			'single prefix, single path; double slashes within prefix' => [
				'psr4_paths' => [
					'Plugin\\Prefix\\Sub\\' => 'src',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/' => 'Plugin\Prefix\Sub',
				],
			],

			'single prefix, single path; path has leading slash' => [
				'psr4_paths' => [
					'Plugin\Prefix' => '/src',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/' => 'Plugin\Prefix',
				],
			],
			'single prefix, single path; path has trailing slash' => [
				'psr4_paths' => [
					'Plugin\Prefix\\' => 'src/',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/' => 'Plugin\Prefix',
				],
			],
			'single prefix, single path; path has leading dot-slash and trailing slash' => [
				'psr4_paths' => [
					'Plugin\Prefix\\' => './src/',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/' => 'Plugin\Prefix',
				],
			],

			'single prefix, multiple paths; simple comma-separated value' => [
				'psr4_paths' => [
					'Plugin\Prefix' => 'src,tests,config',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/'    => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'tests/'  => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'config/' => 'Plugin\Prefix',
				],
			],
			'single prefix, multiple paths; leading/trailing slash variations in comma-separated value' => [
				'psr4_paths' => [
					'Plugin\Prefix\\' => 'src/,./tests/,/config',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/'    => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'tests/'  => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'config/' => 'Plugin\Prefix',
				],
			],
			'single prefix, multiple paths; brackets around comma-separated value and spaces within' => [
				'psr4_paths' => [
					'Plugin\Prefix' => '[src, tests, config]',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/'    => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'tests/'  => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'config/' => 'Plugin\Prefix',
				],
			],
			'single prefix, multiple paths; brackets around comma-separated value and spaces and single quotes within' => [
				'psr4_paths' => [
					'Plugin\Prefix\\' => "[ 'src',   'tests'  , '  config  ']",
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/'    => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'tests/'  => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'config/' => 'Plugin\Prefix',
				],
			],
			'single prefix, multiple paths; brackets around comma-separated value and spaces and double quotes within' => [
				'psr4_paths' => [
					'Plugin\Prefix' => '[ "src/", " ./tests", "/config/ " ]',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/'    => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'tests/'  => 'Plugin\Prefix',
					self::CLEAN_BASEPATH . 'config/' => 'Plugin\Prefix',
				],
			],
			'single prefix, multiple paths; duplicate values' => [
				'psr4_paths' => [
					'Plugin\Prefix\\' => 'src, "./src" , /src/',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/'    => 'Plugin\Prefix',
				],
			],

			'multiple prefixes, variation of paths' => [
				'psr4_paths' => [
					'Plugin\PrefixA'   => 'src',
					'Plugin\PrefixB\\' => 'tests/,config/',
					'Plugin\PrefixC'   => '[ \'subA\sub\\\',   "subB"  ,  subC]',
					'Plugin\PrefixD\\' => '   ,, "   ", \' \' ',
				],
				'expected' => [
					self::CLEAN_BASEPATH . 'src/'      => 'Plugin\PrefixA',
					self::CLEAN_BASEPATH . 'tests/'    => 'Plugin\PrefixB',
					self::CLEAN_BASEPATH . 'config/'   => 'Plugin\PrefixB',
					self::CLEAN_BASEPATH . 'subA/sub/' => 'Plugin\PrefixC',
					self::CLEAN_BASEPATH . 'subB/'     => 'Plugin\PrefixC',
					self::CLEAN_BASEPATH . 'subC/'     => 'Plugin\PrefixC',
				],
			],
		];
	}
}
