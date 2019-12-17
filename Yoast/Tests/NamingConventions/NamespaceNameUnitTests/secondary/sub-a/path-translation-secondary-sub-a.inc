<?php

/*
 * Testing path translation in combination with src_directory.
 */

// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] src,secondary

namespace Yoast\WP\Plugin\Sub_A; // OK.

namespace Yoast\WP\Plugin\Secondary\Sub_A; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
