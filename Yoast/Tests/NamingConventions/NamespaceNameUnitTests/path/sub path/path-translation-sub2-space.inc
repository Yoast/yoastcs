<?php

/*
 * Testing path translation when the path contains characters not allowed in a namespace.
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 */

namespace Yoast\WP\Plugin\Path\Sub Path; // Parse error, illegal space in namespace.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
