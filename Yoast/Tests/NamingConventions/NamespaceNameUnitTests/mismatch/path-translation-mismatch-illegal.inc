<?php

/*
 * Testing path translation when no src_directory matches and a disallowed directory has been passed.
 */

// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] /src/,/../../sub/,/secondary/

namespace Yoast\WP\Plugin\Mismatch; // OK.

namespace Yoast\WP\Plugin\; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
