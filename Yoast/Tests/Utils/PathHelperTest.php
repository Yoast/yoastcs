<?php

namespace YoastCS\Yoast\Tests\Utils;

use PHPUnit\Framework\TestCase;
use YoastCS\Yoast\Utils\PathHelper;

/**
 * Tests for the YoastCS\Yoast\Utils\PathHelper class.
 *
 * @coversDefaultClass \YoastCS\Yoast\Utils\PathHelper
 *
 * @since 3.0.0
 */
final class PathHelperTest extends TestCase {

	/**
	 * Test normalizing a directory path.
	 *
	 * @dataProvider data_normalize_path
	 * @covers       ::normalize_path
	 *
	 * @param string $input    The input string.
	 * @param string $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_normalize_path( $input, $expected ) {
		$this->assertSame( $expected, PathHelper::normalize_path( $input ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_normalize_path() For the array format.
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function data_normalize_path() {
		return [
			'path is dot' => [
				'input'    => '.',
				'expected' => './',
			],
			'path containing forward slashes only with trailing slash' => [
				'input'    => 'my/path/to/',
				'expected' => 'my/path/to/',
			],
			'path containing forward slashes only without trailing slash' => [
				'input'    => 'my/path/to',
				'expected' => 'my/path/to/',
			],
			'path containing forward slashes only with leading and trailing slash' => [
				'input'    => '/my/path/to/',
				'expected' => 'my/path/to/',
			],
			'path containing back-slashes only with trailing slash' => [
				'input'    => 'my\path\to\\',
				'expected' => 'my/path/to/',
			],
			'path containing back-slashes only without trailing slash' => [
				'input'    => 'my\path\to',
				'expected' => 'my/path/to/',
			],
			'path containing back-slashes only with leading, no trailing slash' => [
				'input'    => '\my\path\to',
				'expected' => 'my/path/to/',
			],
			'path containing a mix of forward and backslashes with leading and trailing slash' => [
				'input'    => '/my\path/to\\',
				'expected' => 'my/path/to/',
			],
			'path containing a mix of forward and backslashes without trailing slash' => [
				'input'    => 'my\path/to',
				'expected' => 'my/path/to/',
			],
		];
	}

	/**
	 * Test normalizing the directory separators in a path.
	 *
	 * @dataProvider data_normalize_directory_separators
	 * @covers       ::normalize_directory_separators
	 *
	 * @param string $input    The input string.
	 * @param string $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_normalize_directory_separators( $input, $expected ) {
		$this->assertSame( $expected, PathHelper::normalize_directory_separators( $input ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_normalize_directory_separators() For the array format.
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function data_normalize_directory_separators() {
		return [
			'path is dot' => [
				'input'    => '.',
				'expected' => '.',
			],
			'path containing forward slashes only' => [
				'input'    => 'my/path/to/',
				'expected' => 'my/path/to/',
			],
			'path containing back-slashes only' => [
				'input'    => 'my\path\to\\',
				'expected' => 'my/path/to/',
			],
			'path containing a mix of forward and backslashes' => [
				'input'    => 'my\path/to\\',
				'expected' => 'my/path/to/',
			],
		];
	}

	/**
	 * Test ensuring that a directory path does not start with a leading slash.
	 *
	 * @dataProvider data_remove_leading_slash
	 * @covers       ::remove_leading_slash
	 *
	 * @param string $input    The input string.
	 * @param string $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_remove_leading_slash( $input, $expected ) {
		$this->assertSame( $expected, PathHelper::remove_leading_slash( $input ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_remove_leading_slash() For the array format.
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function data_remove_leading_slash() {
		return [
			'path is dot' => [
				'input'    => '.',
				'expected' => '.',
			],
			'path with leading forward slash' => [
				'input'    => '/my/path/to/',
				'expected' => 'my/path/to/',
			],
			'path with leading back slash' => [
				'input'    => '\my\path\to',
				'expected' => 'my\path\to',
			],
			'path without leading slash' => [
				'input'    => 'my/path/to',
				'expected' => 'my/path/to',
			],
			'path to a file with leading slash' => [
				'input'    => '/file.ext',
				'expected' => 'file.ext',
			],
		];
	}

	/**
	 * Test ensuring that a directory path ends on a trailing slash.
	 *
	 * @dataProvider data_trailingslashit
	 * @covers       ::trailingslashit
	 *
	 * @param string|bool $input    The input string.
	 * @param string      $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_trailingslashit( $input, $expected ) {
		$this->assertSame( $expected, PathHelper::trailingslashit( $input ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_trailingslashit() For the array format.
	 *
	 * @return array<string, array<string, string|bool>>
	 */
	public static function data_trailingslashit() {
		return [
			'path is non-string' => [
				'input'    => false,
				'expected' => '',
			],
			'path is empty string' => [
				'input'    => '',
				'expected' => '',
			],
			'path is dot' => [
				'input'    => '.',
				'expected' => './',
			],
			'path with trailing forward slash' => [
				'input'    => 'my/path/to/',
				'expected' => 'my/path/to/',
			],
			'path with trailing back slash' => [
				'input'    => 'my\path\to\\',
				'expected' => 'my\path\to/',
			],
			'path without trailing slash' => [
				'input'    => 'my/path/to',
				'expected' => 'my/path/to/',
			],
			'path to a file with an extension' => [
				'input'    => 'my/path/to/filename.ext',
				'expected' => 'my/path/to/filename.ext',
			],
			'path to a dot file' => [
				'input'    => 'my/path/to/.gitignore',
				'expected' => 'my/path/to/.gitignore',
			],
			'path ending on a dot' => [
				'input'    => 'my/path/to/dot.',
				'expected' => 'my/path/to/dot./',
			],
			'path with trailing forward slash and the last dir contains a dot' => [
				'input'    => 'my/path/to.ext/',
				'expected' => 'my/path/to.ext/',
			],
		];
	}

	/**
	 * Test stripping one path from the start of another path.
	 *
	 * @dataProvider data_strip_basepath
	 * @covers       ::strip_basepath
	 *
	 * @param string $path     The path of the file.
	 * @param string $basepath The base path to remove.
	 * @param string $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_strip_basepath( $path, $basepath, $expected ) {
		$this->assertSame( $expected, PathHelper::strip_basepath( $path, $basepath ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_strip_basepath() For the array format.
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function data_strip_basepath() {
		return [
			'basepath is empty' => [
				'path'     => '/my/path/too/',
				'basepath' => '',
				'expected' => '/my/path/too/',
			],
			'path NOT starting with other path' => [
				'path'     => '/my/path/too/',
				'basepath' => '/my/path/to/',
				'expected' => '/my/path/too/',
			],
			'path equal to other path, forward slashes' => [
				'path'     => '/my/path/to/',
				'basepath' => '/my/path/to/',
				'expected' => '.',
			],
			'path starting with other path, forward slashes' => [
				'path'     => '/my/path/to/some/sub/directory',
				'basepath' => '/my/path/to/',
				'expected' => 'some/sub/directory',
			],
			'path equal to other path, forward slashes, basepath does not end on slash' => [
				'path'     => '/my/path/to/',
				'basepath' => '/my/path/to',
				'expected' => '.',
			],
			'path starting with other path, forward slashes, basepath does not end on slash' => [
				'path'     => '/my/path/to/some/sub/file.ext',
				'basepath' => '/my/path/to',
				'expected' => 'some/sub/file.ext',
			],
			'path equal to other path, back slashes' => [
				'path'     => 'C:\my\path\to\\',
				'basepath' => 'C:\my\path\to\\',
				'expected' => '.',
			],
			'path starting with other path, back slashes' => [
				'path'     => 'C:\my\path\to\some\sub\directory',
				'basepath' => 'C:\my\path\to\\',
				'expected' => 'some\sub\directory',
			],
			'path equal to other path, back slashes, basepath does not end on slash' => [
				'path'     => 'C:\my\path\to\\',
				'basepath' => 'C:\my\path\to',
				'expected' => '.',
			],
			'path starting with other path, back slashes, basepath does not end on slash' => [
				'path'     => 'C:\my\path\to\some\sub\directory',
				'basepath' => 'C:\my\path\to',
				'expected' => 'some\sub\directory',
			],
		];
	}

	/**
	 * Test verifying whether a certain path starts with another path.
	 *
	 * @dataProvider data_path_starts_with
	 * @covers       ::path_starts_with
	 *
	 * @param string $haystack Directory path to search in.
	 * @param string $needle   Path the haystack path should start with.
	 * @param string $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_path_starts_with( $haystack, $needle, $expected ) {
		$this->assertSame( $expected, PathHelper::path_starts_with( $haystack, $needle ) );
	}

	/**
	 * Data provider.
	 *
	 * @see test_path_starts_with() For the array format.
	 *
	 * @return array<string, array<string, string|bool>>
	 */
	public static function data_path_starts_with() {
		return [
			'path equal to other path, forward slashes' => [
				'haystack' => '/my/path/to/',
				'needle'   => '/my/path/to/',
				'expected' => true,
			],
			'path starting with other path, forward slashes' => [
				'haystack' => '/my/path/to/some/sub/directory',
				'needle'   => '/my/path/to/',
				'expected' => true,
			],
			'path equal to other path, back slashes' => [
				'haystack' => 'C:\my\path\to\\',
				'needle'   => 'C:\my\path\to\\',
				'expected' => true,
			],
			'path starting with other path, back slashes' => [
				'haystack' => 'C:\my\path\to\some\sub\directory',
				'needle'   => 'C:\my\path\to\\',
				'expected' => true,
			],
			'path starting with other path, but slashes are different' => [
				'haystack' => '\my\path\to\some\sub\directory',
				'needle'   => '/my/path/to/',
				'expected' => false,
			],
			'path starting with other path, but case is different' => [
				'haystack' => '/My/path/To/some/Sub/directory',
				'needle'   => '/my/path/to/',
				'expected' => false,
			],
			'path NOT starting with other path, forward slashes' => [
				'haystack' => '/my/path/too/some/sub/directory',
				'needle'   => '/my/path/to/',
				'expected' => false,
			],
			'path NOT starting with other path, back slashes' => [
				'haystack' => 'C:\my\path\too\some\sub\directory',
				'needle'   => 'C:\my\path\to\\',
				'expected' => false,
			],
			'completely different paths' => [
				'haystack' => '/your/subdir/',
				'needle'   => 'my/path/to/',
				'expected' => false,
			],
		];
	}
}
