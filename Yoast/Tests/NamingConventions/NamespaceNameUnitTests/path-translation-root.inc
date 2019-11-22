<?php

/*
 * Testing path translation.
 */

// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin,yoast_plugin

namespace Yoast\WP\Plugin; // OK.

namespace Yoast\WP\Plugin\Foo; // Error.

// Make sure an error is thrown when the wrong prefix is used.
namespace Yoast_Plugin; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
