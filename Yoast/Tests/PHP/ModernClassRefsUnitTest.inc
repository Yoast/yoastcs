<?php

echo __CLASS__; // self::class

echo get_class(); // self::class

echo \get_class(); // self::class
echo namespace\get_class(); // Should be flagged in a non-namespaced file.

echo self::class; // Correct.

$var = get_class( /*comment*/ $this ); // static::class
$var = get_class( null ); // self::class

$var = get_class( object: $this ); // static::class


// Prevent false positives.
$var = get_class( $obj );
$var = get_class( object: $obj );

function get_class() {}
function &  get_class() {}
echo $this  ->    /*comment*/ get_class();
echo self::get_class();
echo Class_Name::get_class();
echo $object /* phpcs:ignore Standard.Category.SniffName-- for reasons */
    ?->get_class();
echo My\Prefix\get_class();
echo \My\Prefix\get_class();
echo namespace\get_class(); // Should NOT be flagged in a namespaced file.
