<?php

/*
 * Scoped namespaces.
 */

/*
 * Testing namespace depth checking.
 */
declare(strict_types=1);

namespace Foo\Bar {} // OK.
namespace Foo\Bar\Baz {} // Warning.
namespace Foo\Bar\Baz\Exception {} // Ok.
namespace Foo\Bar\Baz\Exception\Fro {} // Error.

/*
 * Testing with (correct) prefix and custom level settings.
 */
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
// phpcs:set Yoast.NamingConventions.NamespaceName max_levels 5
// phpcs:set Yoast.NamingConventions.NamespaceName recommended_max_levels 3

namespace Yoast\WP\Plugin\Foo\Bar\Baz {} // OK.
namespace Yoast\WP\Plugin\Foo\Bar\Baz\Fro\Bal\Flo {} // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName max_levels 3
// phpcs:set Yoast.NamingConventions.NamespaceName recommended_max_levels 2
