<?php

/*
 * Testing path translation.
 */

// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin

namespace Yoast\WP\Plugin\Path; // OK.

namespace Yoast\WP\Plugin; // Error.
namespace Yoast\WP\Plugin\Foo; // Error.
namespace Yoast\WP\Plugin\Path\Bar; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
