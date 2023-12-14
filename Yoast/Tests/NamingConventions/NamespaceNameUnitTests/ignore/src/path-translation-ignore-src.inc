<?php

/*
 * Testing path translation in combination with multi-level src_directory.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 * phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] ignore/src
 */

namespace Yoast\WP\Plugin; // OK.

namespace Yoast\WP\Plugin\Ignore\Src; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
