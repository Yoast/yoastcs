<?php

/*
 * Testing path translation in combination with src_directory.
 * Includes testing of correct handling of variations of src_directory settings.
 * Includes testing of matching the longest directory first.
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 * @phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] ./src , ./src/sub-b,src/sub-b,/src
 */

namespace Yoast\WP\Plugin; // OK.

namespace Yoast\WP\Plugin\Sub_B; // Error.
namespace Yoast\WP\Plugin\Src\Sub_B; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
