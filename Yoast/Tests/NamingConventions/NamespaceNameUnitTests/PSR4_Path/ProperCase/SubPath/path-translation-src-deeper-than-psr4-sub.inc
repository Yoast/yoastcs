<?php

/*
 * Testing that PSR-4 path translation takes precedence over src_directory [5].
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 * phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] PSR4_Path/ProperCase
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] Prefix\\=>PSR4_Path
 */

namespace Prefix\ProperCase\SubPath; // Error.

namespace Prefix\SubPath; // Error.
namespace Prefix\ProperCase\Subpath; // Error.
namespace Prefix\ProperCase\subpath; // Error.
namespace Yoast\WP\Plugin\SubPath; // Error.
namespace Yoast\WP\Plugin\PSR4_Path\ProperCase\SubPath; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
// phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[]
