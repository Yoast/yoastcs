<?php

/*
 * Testing namespace depth checking, including tolerance for whitespace and comments.
 */

namespace Foo \     Bar; // OK.
namespace Foo /* comment */
	\Bar
	// phpcs:ignore Stnd.Cat.Sniff -- For reasons.
	\Baz; // Warning.
namespace Foo\Bar\Baz\Fro; // Error.

/*
 * Test passing incorrect prefixes property.
 */
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] \Yoast\WP\Plugin\,_yoast_plugin_

namespace Yoast\WP\ /* comment */ Plugin\Foo\Bar; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz; // Warning.
namespace Yoast \ /* comment */ WP\Plugin\Foo\Bar\Baz\Fro; // Error.

namespace Yoast_Plugin\Foo\Bar; // Warning. Not a namespace prefix, so prefix not taken into account.
namespace Yoast_Plugin\Foo\Bar\Baz\Fro; // Error.

/*
 * Testing with (correct) prefix.
 */
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin

namespace Yoast\WP\Plugin\Foo\Bar; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz; // Warning.
namespace Yoast\WP\Plugin\Foo\Bar\Baz\Fro; // Error.

/*
 * Custom word maximums.
 */
// phpcs:set Yoast.NamingConventions.NamespaceName max_levels 5
// phpcs:set Yoast.NamingConventions.NamespaceName recommended_max_levels 3

namespace Yoast\WP\Plugin\Foo\Bar; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz\Fro; // Warning.
namespace Yoast\WP\Plugin\Foo\Bar\Baz\Fro\Bal\Flo; // Error.

/*
 * Incorrect custom settings (soft > max).
 */
// phpcs:set Yoast.NamingConventions.NamespaceName max_levels 2
// phpcs:set Yoast.NamingConventions.NamespaceName recommended_max_levels 5

namespace Yoast\WP\Plugin\Foo\Bar; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz; // Error.
namespace Yoast\WP\Plugin\Foo\Bar\Baz\Fro; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName max_levels 3
// phpcs:set Yoast.NamingConventions.NamespaceName recommended_max_levels 2

/*
 * Test allowance for `Tests\` and `Tests\Doubles\` just after the prefix.
 */

namespace Yoast\WP\Plugin\Tests\Foo\Bar; // OK.
namespace Yoast\WP\Plugin\Tests\Foo\Bar\Baz; // Warning.
namespace Yoast\WP\Plugin\Tests\Foo\Bar\Baz\Fro; // Error.

namespace Yoast\WP\Plugin\Tests\Doubles\Foo\Bar; // OK.
namespace Yoast\WP\Plugin\Tests\Doubles\Foo\Bar\Baz; // Warning.
namespace Yoast\WP\Plugin\Tests\Doubles\Foo\Bar\Baz\Fro; // Error.

namespace Yoast\WP\Plugin\Foo\Tests\Bar; // Warning, `Tests\` counted as not directly after the prefix.
namespace Yoast\WP\Plugin\Tests\Foo\Doubles\Bar; // Warning, `Doubles\` counted as not directly after the prefix + `Tests\`.
namespace Yoast\WP\Plugin\Doubles\Foo\Bar\Fro; // Error, `Doubles\` counted as not found in combination with `Tests\`.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]

/*
 * Test against false positives for namespace operator and incorrect namespace declarations.
 */

if ( $condition ) {
	namespace Foo\Bar\Baz\Fro; // Ignore. Parse error.
}

echo namespace\function_call(); // Ignore. Operator, not keyword.
