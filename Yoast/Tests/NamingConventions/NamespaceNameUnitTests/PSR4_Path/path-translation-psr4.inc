<?php

/*
 * Testing path translation in combination with psr4_paths, no src directory, no prefixes.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] PSR4_Path\\=>PSR4_Path
 */
namespace PSR4_Path; // OK.

// PSR-4 names are case-sensitive.
namespace psr4_Path; // Error x 2.
namespace Psr4_PATH; // Error x 2.

/*
 * Testing path translation in combination with psr4_paths, no src directory, no prefixes.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] PrefixA\\=>PSR4_Path,PrefixB\\=>OtherPath
 */
namespace PrefixA; // OK.

namespace PrefixB; // Error x 2.
namespace PrefixA\PSR4_PATH; // Error.
namespace PrefixA\Psr4_path; // Error.

/*
 * Testing path translation in combination with multiple psr4_paths + src_directory, no prefixes.
 *
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] PrefixA=>Some_Other,PrefixB=>PSR4_Path,PrefixC=>MyMy
 * phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] PSR4_Path
 */
namespace PrefixB; // OK.

namespace PrefixA; // Error x 2.
namespace PrefixC; // Error x 2.
namespace PrefixB\PSR4_Path; // Error.
namespace PrefixB\psr4_path; // Error.
namespace PrefixB\PSR4_PATH; // Error.

/*
 * Testing path translation in combination with psr4_paths + src_directory, with prefix,
 * when there is no matching PSR4 path due to a case-mismatch.
 *
 * In that case, the "normal" rules apply.
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 * phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] .
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] Prefix\\=>pSr4_path
 */
namespace Yoast\WP\Plugin\PSR4_Path; // OK.
namespace Yoast\WP\Plugin\Psr4_PATH; // OK.
namespace Yoast\WP\Plugin\psr4_path; // OK.

namespace Prefix; // Error x 2.
namespace Prefix\PSR4_Path; // Error x 2.

/*
 * Testing path translation in combination with psr4_paths + src_directory, with prefix,
 * when there is no matching PSR4 path.
 *
 * In that case, the "normal" rules apply.
 *
 * @phpcs:set Yoast.NamingConventions.NamespaceName prefixes[] Yoast\WP\Plugin
 * phpcs:set Yoast.NamingConventions.NamespaceName src_directory[] .
 * phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[] PrefixA\\=>not-this-path,PrefixB\\=>Invalid
 */
namespace Yoast\WP\Plugin\PSR4_Path; // OK.
namespace Yoast\WP\Plugin\Psr4_PATH; // OK.
namespace Yoast\WP\Plugin\psr4_path; // OK.

namespace PrefixA; // Error x 2.
namespace PrefixA\PSR4_Path; // Error x 2.
namespace PrefixB; // Error x 2.
namespace PrefixB\PSR4_Path; // Error x 2.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.NamespaceName prefixes[]
// phpcs:set Yoast.NamingConventions.NamespaceName src_directory[]
// phpcs:set Yoast.NamingConventions.NamespaceName psr4_paths[]
