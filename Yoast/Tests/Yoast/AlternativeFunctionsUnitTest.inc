<?php

$json = WPSEO_Utils:format_json_encode( $thing ); // OK.

// Don't trigger on calls to functions which are not the PHP or WP native functions.
$json = Class_Other_Plugin::json_encode( $thing ); // OK.
$json = $obj->wp_json_encode( $thing ); // OK.
$json = MyNamespace\json_encode( $thing ); // OK.
$json = namespace\wp_json_encode( $thing );  // OK.

// The sniff should trigger on these.
$json = json_encode( $thing ); // Error.
$json = wp_json_encode( $thing ); // Error.
return function_call(nested_call(json_encode( $thing ))); // Error.

$json = json_encode ($value, $options); // Error, non-fixable.
$json = wp_json_encode( $data, $options, $depth ); // Error, non-fixable.

namespace Yoast\CMS\Plugin\Dir;

$json = json_encode( $thing ); // Error.
$json = wp_json_encode( $thing ); // Error.
