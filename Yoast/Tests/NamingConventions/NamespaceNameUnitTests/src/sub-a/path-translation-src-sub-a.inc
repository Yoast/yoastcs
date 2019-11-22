<?php

/*
 * Testing path translation in combination with src_directory.
 * Includes testing of correct handling of variations of src_directory settings.
 */

// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] /src/

namespace Yoast\WP\Plugin\Sub_A; // OK.

namespace Yoast\WP\Plugin\Src\Sub_A; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
