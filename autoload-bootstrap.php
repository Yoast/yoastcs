<?php
/**
 * YoastCS: Bootstrap file for running YoastCS as a stand-alone standard.
 *
 * While YoastCS itself and _most_ of its dependencies relies on PHP_CodeSniffer
 * for the class autoloading, the Slevomat Coding Standard uses an external
 * dependency which is loaded via the Composer `vendor/autoload.php` file.
 * This means that YoastCS now *also* needs to load the `vendor/autoload.php`
 * file and this bootstrap file handles that.
 *
 * @package Yoast\YoastCS
 * @since   3.0.0
 */

if ( defined( 'YOASTCS_PHPCS_AUTOLOAD_SET' ) === false ) {

	// Check if this is a stand-alone installation.
	if ( is_file( __DIR__ . '/vendor/autoload.php' ) ) {
		require_once __DIR__ . '/vendor/autoload.php';
	}

	define( 'YOASTCS_PHPCS_AUTOLOAD_SET', true );
}
