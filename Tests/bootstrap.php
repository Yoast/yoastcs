<?php
/**
 * Bootstrap file for running the tests.
 *
 * Load the PHPCS autoloader and the WPCS PHPCS cross-version helpers.
 *
 * Code has been based on the phpcs3-bootstrap.php used in the WordPress Coding Standards project
 *
 * @link: https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
 *
 * @package Yoast\YoastCS
 * @link    https://github.com/Yoast/yoastcs
 * @license https://opensource.org/licenses/MIT MIT
 * @since   1.1.0
 */

if ( ! defined( 'PHP_CODESNIFFER_IN_TESTS' ) ) {
	define( 'PHP_CODESNIFFER_IN_TESTS', true );
}

/**
 * Builds the directory path for the given parts.
 *
 * @param array $parts Parts to convert to a path.
 *
 * @return string The path usable for the current system.
 */
function yoastcs_test_bootstrap_get_path( $parts ) {
	return implode( DIRECTORY_SEPARATOR, $parts );
}

$ds              = DIRECTORY_SEPARATOR;
$baseComposerDir = dirname( __DIR__ );

// Get the PHPCS dir from an environment variable.
$phpcsDir         = getenv( 'PHPCS_DIR' );
$phpcsComposerDir = yoastcs_test_bootstrap_get_path(
	array( $baseComposerDir, 'vendor', 'squizlabs', 'php_codesniffer' )
);

// This may be a Composer install.
if ( false === $phpcsDir && is_dir( $phpcsComposerDir ) ) {
	$phpcsDir = $phpcsComposerDir;
}
elseif ( false !== $phpcsDir ) {
	$phpcsDir = realpath( $phpcsDir );
}

// Try and load the PHPCS autoloader.
if ( false !== $phpcsDir && file_exists( yoastcs_test_bootstrap_get_path( array( $phpcsDir, 'autoload.php' ) ) ) ) {
	require_once yoastcs_test_bootstrap_get_path( array( $phpcsDir, 'autoload.php' ) );

	/*
	 * As of PHPCS 3.1, PHPCS support PHPUnit 6.x, but needs a bootstrap, so
	 * load it if it's available.
	 */
	if ( file_exists( yoastcs_test_bootstrap_get_path( array( $phpcsDir, 'tests', 'bootstrap.php' ) ) ) ) {
		require_once yoastcs_test_bootstrap_get_path( array( $phpcsDir, 'tests', 'bootstrap.php' ) );
	}
}
else {
	echo 'Uh oh... can\'t find PHPCS.

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `PHPCS_DIR` environment variable in your phpunit.xml file
pointing to the PHPCS directory.
';

	die( 1 );
}

// Get the WPCS dir from an environment variable.
$wpcsDir         = getenv( 'WPCS_DIR' );
$wpcsComposerDir = yoastcs_test_bootstrap_get_path( array( $baseComposerDir, 'vendor', 'wp-coding-standards', 'wpcs' ) );

// This may be a Composer install.
if ( false === $wpcsDir && is_dir( $wpcsComposerDir ) ) {
	$wpcsDir = $wpcsComposerDir;
}
elseif ( false !== $wpcsDir ) {
	$wpcsDir = realpath( $wpcsDir );
}

// Try and load the WPCS class aliases file.
$wpcsAliasFilename = yoastcs_test_bootstrap_get_path( array( $wpcsDir, 'WordPress', 'PHPCSAliases.php' ) );
if ( file_exists( $wpcsAliasFilename ) ) {
	require_once $wpcsAliasFilename;
}
else {
	echo 'Uh oh... can\'t find WPCS.

If you use Composer, please run `composer install`.
Otherwise, make sure you set a `WPCS_DIR` environment variable in your phpunit.xml file
pointing to the WPCS directory.
';

	die( 1 );
}

unset( $ds, $baseComposerDir, $phpcsDir, $phpcsComposerDir, $wpcsDir, $wpcsComposerDir, $wpcsAliasFilename );
