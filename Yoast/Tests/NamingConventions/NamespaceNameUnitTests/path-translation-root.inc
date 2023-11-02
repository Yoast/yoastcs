<?php

/*
 * Testing path translation.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin,yoast_plugin
 */

namespace Yoast\WP\Plugin; // OK.

namespace Yoast\WP\Plugin\Foo; // Error.

// Make sure an error is thrown when the wrong type of prefix (non-namespace-like) is used.
namespace Yoast_Plugin; // Error for wrong path-to-namespace + error for not using valid prefix.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
