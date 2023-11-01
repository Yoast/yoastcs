<?php

/*
 * Testing path translation.
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 */

namespace Yoast\WP\Plugin\path\sub_path; // OK.
namespace Yoast\WP\Plugin\Path\Sub_Path; // OK.

namespace Yoast\WP\Plugin\Path\Sub.Path; // Parse error, illegal concatenation in namespace, but that's not the concern of this sniff.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
