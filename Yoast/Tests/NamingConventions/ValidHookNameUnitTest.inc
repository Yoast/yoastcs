<?php

/*
 * Verify that the original functionality of the upstream sniff is still working as expected.
 * Functionality when no prefixes have been passed, should be unchanged.
 */

do_action( $Hook_name ); // OK.
do_action( "admin_head" ); // OK.
do_action( 'admin_head_' .  get_ID() . '_action' ); // OK.
do_action( "admin_head_$Post" ); // OK.
do_action( 'prefix_block_' . $block['block-Name'] ); // OK.

do_action( "adminHead" ); // Error - use lowercase.
do_action( 'admin_Head_' . $Type . '_Action' ); // Error - use lowercase.
do_action( "admin_head-$hook_suffix" ); // Warning - use underscore.
apply_filters( 'adminHead', $variable ); // Error - use lowercase.
do_action( "admin_Head_{$post->ID}_Action" ); // Error - use lowercase.
do_action( "admin_Head_{$obj->Values[3]->name}-Action_{$obj->Values[3]->name}_Action" ); // Error - use lowercase + warning about dash.

/*
 * Verify the Yoast specific changes to allow for the plugin prefix, while still checking
 * that the rest of the hook name is lowercase and underscore separated.
 *
 * @phpcs:disable Yoast.NamingConventions.ValidHookName.WrongPrefix
 */

// phpcs:set Yoast.NamingConventions.ValidHookName prefixes[] Yoast\WP\Plugin,yoast_plugin

apply_filters_ref_array( 'yoast_plugin_some_hook_name', $var ); // OK.
apply_filters( 'Yoast\WP\Plugin\some_hook_name', $var ); // OK.

do_action_ref_array( 'Yoast\WP\Plugin\some-hook\name', $var ); // Warning - disallowed word separators (after prefix).
apply_filters( 'Yoast\WP\Plugin\some_HookName', $var ); // Error - uppercase chars (after prefix).
apply_filters_ref_array( 'yoast_plugin_Some-Hook-Name', $var ); // Error + warning, uppercase chars + dashes (after prefix).

// OK, ignored. Hooks do not start with correct prefix, while valid prefixes have been passed.
do_action( 'Yoast\WP\AnotherPlugin\some_hook_name', $var );
apply_filters( 'some_hook_name_yoast_plugin_Prefix_not-at_start', $var );

// Second use of the prefix (correctly) not taken into account as not found at the start of the hook name.
do_action( 'Yoast\WP\Plugin\hook_' . $type . 'Yoast\WP\Plugin\hook' ); // Error + warning x 2, prefix repeated, compound name.

// Test handing of compound hook names with the prefix as stand-alone string.
apply_filters( 'Yoast\WP\Plugin\\' . $type . '_' . $sub, $var ); // Warning at severity 3 compound name.

// Test handling of escaped slash in double quotes string.
$var = apply_filters( "Yoast\\WP\\Plugin\\{$obj->prop['type']}_details", $var ); // Warning at severity 3 compound name.

// phpcs:enable

/*
 * Test Yoast specific sniff additions for checking hook name length and such.
 *
 * These checks are only in effect if a prefix is found.
 * The WPCS PrefixAllGlobals sniff checks that a prefix is used, that's outside the scope of this sniff.
 */

/*
 * Simple strings, no prefix. Includes testing handling of comments.
 */
do_action(
	// phpcs:ignore Stnd.Cat.Sniff -- For reasons.
	'some_hook_name_too_long' /* comment */
); // OK. Plugin prefix not found, so ignored.

/*
 * Non simple strings.
 */
// phpcs:set Yoast.NamingConventions.ValidHookName prefixes[] Yoast\WP\Plugin,yoast_plugin

do_action( "Yoast\\WP\\Plugin\\some_{$variable}_hook_name"); // Warning at severity 3.
do_action( 'yoast_plugin_some_' . 'hook_' . 'name' ); // Warning at severity 3 + warning wrong prefix.

/*
 * Test passing "unclean" prefixes property.
 */
// phpcs:set Yoast.NamingConventions.ValidHookName prefixes[] \Yoast\WP\Plugin\,_yoast_plugin_

apply_filters( 'Yoast\WP\Plugin\some_hook_name', $var ); // OK.
apply_filters_ref_array( 'yoast_plugin_some_hook_name', $var ); // Warning - wrong prefix.

apply_filters( 'Yoast\WP\Plugin\some_hook_name_too_long', $var ); // Error - too long.
do_action_ref_array( "yoast_plugin_some_hook_name_too_long", $var ); // Error - too long + warning - wrong prefix.

/*
 * Testing with clean prefixes.
 *
 * Passing both old-style and new-style prefixes during the transition period.
 */
// phpcs:set Yoast.NamingConventions.ValidHookName prefixes[] Yoast\WP\Plugin,Yoast\WP\Plugin\Test,yoast_plugin

do_action( 'Yoast\WP\Plugin\Test\some_hook_name', $var ); // OK.
do_action_ref_array( "yoast_plugin_some_hook_name", $var ); // Warning - wrong prefix.

apply_filters( 'Yoast\WP\Plugin\some_hook_name_too_long', $var ); // Error - too long.
do_action_ref_array( "yoast_plugin_some_hook_name_too_long", $var ); // Error - too long + warning - wrong prefix.

/*
 * Custom word maximums.
 */
// phpcs:set Yoast.NamingConventions.ValidHookName max_words 5
// phpcs:set Yoast.NamingConventions.ValidHookName recommended_max_words 2

apply_filters( 'Yoast\WP\Plugin\some_hook', $var ); // OK.

do_action( 'Yoast\WP\Plugin\some_hook_name', $var ); // Warning - over recommended length.
do_action_ref_array( 'Yoast\WP\Plugin\some_hook_name_which_is_too_long', $var ); // Error.

do_action( 'yoast_plugin_some_hook_name', $var ); // Warning x 2 - over recommended length + wrong prefix.
do_action_ref_array( 'yoast_plugin_some_hook_name_which_is_too_long', $var ); // Error - length + warning - wrong prefix..

/*
 * Incorrect custom settings (soft > max).
 */
// phpcs:set Yoast.NamingConventions.ValidHookName max_words 2
// phpcs:set Yoast.NamingConventions.ValidHookName recommended_max_words 5

do_action( 'Yoast\WP\Plugin\some_hook_name', $var ); // Error.

// Reset word maximums.
// phpcs:set Yoast.NamingConventions.ValidHookName max_words 4
// phpcs:set Yoast.NamingConventions.ValidHookName recommended_max_words 4

/*
 * Test handling of double slashes in prefixes.
 * - Double slashes in single quoted strings are generally not needed, but will not cause problems.
 * - Single slashed in double quoted strings could be problematic, so escaping the slashes is recommended.
 */

// Correct (single quoted).
apply_filters( 'Yoast\WP\Plugin\hookname', $var); // OK.
apply_filters( 'Yoast\\WP\\Plugin\\hookname', $var); // OK.

// Correct (double quoted).
apply_filters( "Yoast\\WP\\Plugin\\hook{$name}", $var); // OK, warning at severity 3 for word count.
apply_filters( "Yoast\\WP\\Plugin\\hookname", $var); // OK.

// Incorrect (double quoted - the `\` needs to be escaped).
apply_filters( "Yoast\WP\Plugin\hookname", $var); // Warning, missing escaping x 3.
apply_filters( "Yoast\WP\\Plugin\hook{$name}", $var); // Warning, missing escaping x 2 + warning at severity 3 for word count.


// Safeguard support for PHP 8.0+ named parameters.
do_action_ref_array( hook: 'My-Hook', args: $args ); // OK. Well, not really, but using the wrong parameter name, so not our concern.
do_action_ref_array( args: $args, hook_name: 'Yoast\WP\Plugin\Test\some_hook_name', ); // OK.
do_action_ref_array( args: $args, hook_name: "yoast_plugin_some_hook_name", ); // Warning - wrong prefix.
do_action_ref_array( args: $args, hook_name: 'Yoast\WP\Plugin\some_hook', ); // OK.
do_action_ref_array( args: $args, hook_name: 'Yoast\WP\Plugin\some_hook_name_which_is_too_long', ); // Error - too long.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.ValidHookName prefixes[]
