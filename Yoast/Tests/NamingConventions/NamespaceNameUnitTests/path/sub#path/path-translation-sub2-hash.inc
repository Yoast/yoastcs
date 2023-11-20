<?php

/*
 * Testing path translation.
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 */

namespace Yoast\WP\Plugin\path\sub_path; // OK.
namespace Yoast\WP\Plugin\Path\Sub_Path; // OK.

namespace Yoast\WP\Plugin\Path\Sub#Path; // OK. Parse error, unexpected end of file, sniff won't be able to reliably retrieve the namespace.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
