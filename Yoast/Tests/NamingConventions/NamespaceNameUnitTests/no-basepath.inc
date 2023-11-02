<?php

/*
 * Testing namespace depth checking, including tolerance for whitespace and comments.
 * Note: comments and whitespace in namespace names are a parse error since PHP 8.0. This was fine before that time.
 */
namespace Foo \     Bar; // OK.
namespace Foo /* comment */
	\Bar
	// phpcs:ignore Stnd.Cat.Sniff -- For reasons.
	\Baz; // Warning.
namespace Foo\Bar\Baz\Fro; // Error.

/*
 * Test passing incorrect prefixes property.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] \Yoast\WP\Plugin\,_yoast_plugin_
 */
namespace Yoast\WP\ /* comment */ Plugin\Foo\Bar; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz; // Warning.
namespace Yoast \ /* comment */ WP\Plugin\Foo\Bar\Baz\Fro; // Error.

namespace Yoast_Plugin\Foo\Bar; // Warning for object depth. Not a namespace prefix, so prefix not taken into account. Error for not using prefix.
namespace Yoast_Plugin\Foo\Bar\Baz\Fro; // Error for object depth + not using valid prefix.

/*
 * Testing with (correct) prefix.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 */
namespace Yoast\WP\Plugin\Foo\Bar; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz; // Warning.
namespace Yoast\WP\Plugin\Foo\Bar\Baz\Fro; // Error.

/*
 * Custom word maximums.
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName max_levels 5
 * @phpcs:set Yoast.NamingConventions.NamespaceName recommended_max_levels 3
 */
namespace Yoast\WP\Plugin\Foo\Bar; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz\Fro; // Warning.
namespace Yoast\WP\Plugin\Foo\Bar\Baz\Fro\Bal\Flo; // Error.

/*
 * Incorrect custom settings (soft > max).
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName max_levels 2
 * @phpcs:set Yoast.NamingConventions.NamespaceName recommended_max_levels 5
 */
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

/*
 * Test handling of namespace names with reserved keywords in them (PHP 8.0+).
 */
namespace Yoast\WP\Plugin\Trait\Sub; // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Global; // Warning.
namespace Yoast\WP\Plugin\Foo\Case\Bar\Default; // Error.

/*
 * Testing an error is thrown for not using a prefix from the validated prefixes array - single valid prefix.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 */

namespace Yoast\WP\Plugin\Foo; // OK.
namespace Yoast\WP\Foo; // Error (+ warning for level depth as prefix not correct).
namespace Yoast\Foo; // Error.
namespace Foo\Bar; // Error.

/*
 * Testing an error is thrown for not using a prefix from the validated prefixes array - multiple valid prefixes.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin\SubA,Yoast\WP\Plugin\SubB,Yoast\WP\Plugin
 */

namespace Yoast\WP\Plugin\SubA\Foo; // OK.
namespace Yoast\WP\Plugin\SubB; // OK.
namespace Yoast\WP\Plugin\Foo; // OK.
namespace Yoast\WP\Foo; // Error (+ warning for level depth as prefix not correct).
namespace Yoast\Foo; // Error.
namespace Foo\Bar; // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]

/*
 * Test against false positives for namespace operator and incorrect namespace declarations.
 */

if ( $condition ) {
	namespace Foo\Bar\Baz\Fro; // Ignore. Parse error.
}

echo namespace\function_call(); // Ignore. Operator, not keyword.
