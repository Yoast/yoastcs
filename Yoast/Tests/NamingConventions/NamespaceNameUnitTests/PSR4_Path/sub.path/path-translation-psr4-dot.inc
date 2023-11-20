<?php

/*
 * Testing path translation.
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] Yoast\WP\Plugin=>PSR4_Path
 */

namespace Yoast\WP\Plugin\PSR4_Path\Sub.Path; // Error: invalid path. Would cause parse error, illegal concatenation in namespace.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[]
