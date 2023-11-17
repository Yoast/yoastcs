<?php

/*
 * Testing path translation in combination with src_directory.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] Yoast\WP\Plugin\\=>PSR4_Path
 */

namespace Yoast\WP\Plugin\lowercase; // OK.

namespace Yoast\WP\Plugin\Lowercase; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[]
