<?php

echo __CLASS__; // self::class

echo get_class(); // self::class

echo \get_class(); // self::class
echo namespace\get_class(); // Should be flagged in a non-namespaced file.

echo self::class; // Correct.

$var = get_class( /*comment*/ $this ); // static::class
$var = get_class( null ); // self::class

$var = get_class( object: $this ); // static::class

$var = get_called_class(); // static::class

class NonFinalClass extends Baz {
    function bar() {
        $var = get_parent_class(); // parent::class

        $var = get_parent_class('SomeClass'); // change the param to SomeClass::class BUT MAY also need for a `use` statement to be added, depending on whether the class has a namespace. Prevent adding use statement if it already exists or if this is a use alias.
        $var = get_parent_class('Partially\Qualified\SomeClass'); // change the param to SomeClass::class BUT MAY also need for a `use` statement to be added, depending on whether the class has a namespace. Prevent adding use statement if it already exists.
        $var = get_parent_class('\Fully\Qualified\SomeClass'); // change the param to SomeClass::class BUT MAY also need for a `use` statement to be added, depending on whether the class has a namespace. Prevent adding use statement if it already exists.

        $var = get_parent_class( $this ); // DO NOT TOUCH.
        $var = get_parent_class( $obj ); // DO NOT TOUCH.
    }
}

final class FinalClass extends Baz {
    function bar() {
        $var = get_parent_class( $this ); // parent::class
    }
}


// Prevent false positives.
$var = get_class( wrongParamName: $this ); // Wrong param.
$var = get_class( $obj, $this ); // Wrong param.

$var = get_class( $obj );
$var = get_class( object: $obj );

function get_class() {}
function &  get_class() {}
echo $this->get_class();
echo $this  ->    /*comment*/ get_class();
echo self::get_class();
echo Class_Name::get_class();
echo $object /* phpcs:ignore Standard.Category.SniffName-- for reasons */
    ?->get_class();
echo My\Prefix\get_class();
echo \My\Prefix\get_class();
echo namespace\get_class(); // Should NOT be flagged in a namespaced file.
