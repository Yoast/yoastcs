<?php

/*
 * Testing path translation in combination with multiple src_directories.
 * Includes testing of correct handling of variations of src_directory settings.
 */

// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] ./src/,.\secondary\

namespace Yoast\WP\Plugin; // OK.

namespace Yoast\WP\Plugin\Secondary; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
