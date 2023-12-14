<?php

// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\Plugin

/*
 * Testing PSR-4 path translation, no src_directory.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] Yoast\WP\Plugin\\=>PSR4_Path
 */
namespace Yoast\WP\Plugin\ProperCase; // OK.

namespace Yoast\Plugin\PSR4_Path\ProperCase; // Error x 3.
namespace Yoast\Plugin\ProperCase; // Error x 2 + warning.
namespace Yoast\WP\Plugin\propercase; // Error.
namespace Yoast\WP\Plugin\Propercase; // Error.
namespace Yoast\WP\Plugin\PROPERCASE; // Error.

/*
 * Testing that PSR-4 path translation takes precedence over src_directory [1].
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] PSR4_Path/ProperCase
 * @phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] Yoast\WP\Plugin=>PSR4_Path
 */
namespace Yoast\WP\Plugin\ProperCase; // OK.

namespace Yoast\Plugin; // Error x 2.
namespace Yoast\Plugin\ProperCase; // Error x 2 + warning.
namespace Yoast\WP\Plugin; // Error.
namespace Yoast\WP\Plugin\PSR4_Path\ProperCase; // Error.

/*
 * Testing that PSR-4 path translation takes precedence over src_directory [2].
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] PSR4_Path
 * @phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] Yoast\WP\Plugin\\=>.
 */
namespace Yoast\WP\Plugin\PSR4_Path\ProperCase; // OK.

namespace Yoast\Plugin\PSR4_Path\ProperCase; // Error x 3.
namespace Yoast\Plugin\ProperCase; // Error x 2 + warning.
namespace Yoast\WP\Plugin; // Error.
namespace Yoast\WP\Plugin\ProperCase; // Error.

/*
 * Testing that PSR-4 path translation takes precedence over src_directory [3].
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] PSR4_Path
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] Yoast\WP\Plugin=>PSR4_Path/ProperCase
 */
namespace Yoast\WP\Plugin; // OK.

namespace Yoast\Plugin; // Error x 2.
namespace Yoast\Plugin\ProperCase; // Error x 2 + warning.
namespace Yoast\Plugin\PSR4_Path\ProperCase; // Error x 3.
namespace Yoast\WP\Plugin\ProperCase; // Error.
namespace Yoast\WP\Plugin\PSR4_Path\ProperCase; // Error.

/*
 * Testing that PSR-4 path translation takes precedence over src_directory [4].
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] .
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] Yoast\WP\Plugin\\=>PSR4_Path/
 */
namespace Yoast\WP\Plugin\ProperCase; // OK.

namespace Yoast\Plugin\ProperCase; // Error x 2 + warning.
namespace Yoast\Plugin\PSR4_Path\ProperCase; // Error x 3.
namespace Yoast\WP\Plugin; // Error.
namespace Yoast\WP\Plugin\PSR4_Path\ProperCase; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
// phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[]
