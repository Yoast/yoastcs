<?php

/*
 * Testing path translation.
 */

// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin

namespace Yoast\WP\Plugin\path\sub_path; // OK.
namespace Yoast\WP\Plugin\Path\Sub_Path; // OK.

namespace Yoast\WP\Plugin; // Error.
namespace Yoast\WP\Plugin\Path; // Error.
namespace Yoast\WP\Plugin\Path\Bar; // Error.
namespace Yoast\WP\Plugin\Path\Sub-Path; // Error. (Parse error too, illegal dash in namespace).

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
