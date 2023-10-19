<?php
/**
 * YoastCS: Bootstrap file for running the tests.
 *
 * - Load the PHPCS PHPUnit bootstrap file providing cross-version PHPUnit support.
 *   {@link https://github.com/squizlabs/PHP_CodeSniffer/pull/1384}
 * - Load the Composer autoload file.
 * - Automatically limit the testing to the YoastCS tests.
 *
 * @package Yoast\YoastCS
 * @since   3.0.0
 */

use PHP_CodeSniffer\Util\Standards;

if ( defined( 'PHP_CODESNIFFER_IN_TESTS' ) === false ) {
	define( 'PHP_CODESNIFFER_IN_TESTS', true );
}

/*
 * Load the necessary PHPCS files.
 */
// Get the PHPCS dir from an environment variable.
$phpcs_dir           = getenv( 'PHPCS_DIR' );
$composer_phpcs_path = __DIR__ . '/vendor/squizlabs/php_codesniffer';

if ( $phpcs_dir === false && is_dir( $composer_phpcs_path ) ) {
	// PHPCS installed via Composer.
	$phpcs_dir = $composer_phpcs_path;
}
elseif ( $phpcs_dir !== false ) {
	/*
	 * PHPCS in a custom directory.
	 * For this to work, the `PHPCS_DIR` needs to be set in a custom `phpunit.xml` file.
	 */
	$phpcs_dir = realpath( $phpcs_dir );
}

// Try and load the PHPCS autoloader.
if ( $phpcs_dir !== false
	&& file_exists( $phpcs_dir . '/autoload.php' )
	&& file_exists( $phpcs_dir . '/tests/bootstrap.php' )
) {
	require_once $phpcs_dir . '/autoload.php';
	require_once $phpcs_dir . '/tests/bootstrap.php'; // PHPUnit 6.x+ support.
}
else {
	echo 'Uh oh... can\'t find PHPCS.

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `PHPCS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCS directory.
';

	die( 1 );
}

/*
 * Set the PHPCS_IGNORE_TEST environment variable to ignore tests from other standards.
 */
$yoast_standards = [
	'Yoast' => true,
];

$all_standards   = Standards::getInstalledStandards();
$all_standards[] = 'Generic';

$standards_to_ignore = [];
foreach ( $all_standards as $standard ) {
	if ( isset( $yoast_standards[ $standard ] ) === true ) {
		continue;
	}

	$standards_to_ignore[] = $standard;
}

$standards_to_ignore_string = implode( ',', $standards_to_ignore );

// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv -- This is not production, but test code.
putenv( "PHPCS_IGNORE_TESTS={$standards_to_ignore_string}" );

// Clean up.
unset( $phpcs_dir, $composer_phpcs_path, $all_standards, $standards_to_ignore, $standard, $standards_to_ignore_string );
