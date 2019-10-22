<?php

// Testing object declarations in a file with a namespace.

namespace Yoast\Plugin;

/*
 * All OK.
 */
class Admin {}
class Class_Name {}
class Three_Part_Name {}

interface My_Interface_Name {}
trait Some_Trait_Name {}

/*
 * Too long.
 */

class Too_Long_Class_Name {} // Error.
interface This_Interface_Name_Is_Too_Long {} // Error.
trait Trait_Name_Is_Too_Long {} // Error.

/*
 * Custom settings.
 */

// phpcs:set Yoast.NamingConventions.ObjectNameDepth max_words 5
// phpcs:set Yoast.NamingConventions.ObjectNameDepth recommended_max_words 2

class Three_Part_Name {} // Warning.
class Six_Part_Class_Name_Too_Long {} // Error.

/*
 * Incorrect custom settings (soft > max).
 */

// phpcs:set Yoast.NamingConventions.ObjectNameDepth max_words 2
// phpcs:set Yoast.NamingConventions.ObjectNameDepth recommended_max_words 5

class Three_Part_ClassName {} // Error.
class Seven_Part_Class_Name_Too_Long_Too {} // Error.

// Reset to default settings.
// phpcs:set Yoast.NamingConventions.ObjectNameDepth max_words 3
// phpcs:set Yoast.NamingConventions.ObjectNameDepth recommended_max_words 3

/*
 * Ignore deprecated objects.
 */

/**
 * Class description, no @deprecated tag.
 *
 * @since x.x.x
 */
class Active_Class_With_Too_Long_Class_Name {} // Error.

/**
 * Class description.
 *
 * @deprecated x.x.x
 */
class Deprecated_Class_With_Too_Long_Class_Name {} // OK.
