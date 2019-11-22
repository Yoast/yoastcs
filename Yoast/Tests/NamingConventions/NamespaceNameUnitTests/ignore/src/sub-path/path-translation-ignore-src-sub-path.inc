<?php

/*
 * Testing path translation in combination with multi-level src_directory.
 */

// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] ignore/src

namespace Yoast\WP\Plugin\Sub_Path; // OK.

namespace Yoast\WP\Plugin; // Error.
namespace Yoast\WP\Plugin\Src\Sub_Path; // Error.
namespace Yoast\WP\Plugin\Ignore\Src\Sub_Path; // Error + warning about depth.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
