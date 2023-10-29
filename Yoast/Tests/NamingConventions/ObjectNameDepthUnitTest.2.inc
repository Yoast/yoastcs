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

/**
 * Class description, no @deprecated tag.
 *
 * @since x.x.x
 */
#[Some_Attribute]
final class Active_Class_With_Attribute_With_Too_Long_Class_Name {} // Error.

/**
 * Class description.
 *
 * @deprecated x.x.x
 */
#[Attribute_One]
#[Attribute_Two]
abstract class Deprecated_Class_With_Attribute_With_Too_Long_Class_Name {} // OK.

/*
 * Allow for a `_Test` suffix in classes within the unit test suite.
 */
class Three_Word_Name_Test {} // Error.

class Three_Word_Name_Test extends TestCase {} // OK.
class Three_Word_Name_Test extends \WP_UnitTestCase {} // OK.

class Four_Word_Long_Name_Test extends TestCase {} // Error.

// Similarly for Double/Mock classes.
class Three_Word_Name_Double {} // Error.
class Three_Word_Name_Mock {} // Error.

class Three_Word_Name_Double extends Three_Word_Name {} // OK.
class Three_Word_Name_Mock extends Some_Class {} // OK.

/*
 * Make sure underscore prefixed names have a correct count.
 */
class __Three_Word_Name {} // OK.
class __Four_Four_Word_Name {} // Error.

/*
 * Make sure CamelCaps class names are also handled by this sniff.
 */
class ThreePartName {}
interface MyInterfaceName {}
trait SomeACRONYMName {} // Error - false positive, this will be fixed in a later iteration on this sniff.

class TooLongClassName {} // Error.
interface ThisInterfaceNameIsTooLong {} // Error.
trait TraitNameIsTooLong {} // Error.

// Now, let's also make sure the test/double handling works for CamelCaps class names + class names with the suffix in an unconventional case.
class ThreeWordNameTest extends TestCase {} // OK.
class ThreeWordNameDouble extends ThreeWordName {} // OK.
class Three_Word_Name_test extends TestCase {} // OK.
class Three_Word_Name_MOCK extends Some_Class {} // OK.

/*
 * TestCase classes should also be allowed extra word length.
 */
class ThreeWordNameTestCase extends TestCase {} // OK.
class Three_Word_Name_TestCase extends TestCase {} // OK.

/*
 * Allow for PHP 8.1+ enums.
 */
enum My_Enum_Name {} // OK.
enum MyEnumName {} // OK.
enum Too_Long_Enum_Name: int {} // Error.
enum TooLongEnumName: int {} // Error.
enum Three_Word_Name_Mock: string implements Word_Interface {} // OK.

/**
 * Class description, no @deprecated tag.
 *
 * @since x.x.x
 */
#[Some_Attribute]
readonly class Active_Class_With_Attribute_With_Too_Long_Class_Name {} // Error.

/**
 * Class description.
 *
 * @deprecated x.x.x
 */
final readonly class Deprecated_Readonly_Class_With_Too_Long_Class_Name {} // OK.
