<?php

class NotATestClass {

	/**
	 * Docblock.
	 */
	function testSomething() {}
}

/**
 * @covers ClassName
 */
class ClassLevelCoversTest {

	/**
	 * Docblock.
	 */
	public function testMissingCoversTag() {}

	/**
	 * Docblock.
	 *
	 * @coversNothing
	 */
	public function testCoversNothingTag() {}
}

/**
 * @coversNothing
 */
final class ClassLevelCoversNothingTest {

	/**
	 * Docblock.
	 */
	public function testMissingCoversTag() {}

	/**
	 * Docblock.
	 *
	 * @covers ::something
	 */
	public function testCoversTag() {}
}

class ClassNameTest {

	public function testMissingDocblock() {}

	/**
	 * Docblock.
	 */
	function notATestMethod() {}

	/**
	 * Docblock.
	 */
	public function testMissingCoversTag() {}

	/**
	 * Docblock.
	 *
	 * @coversNothing
	 */
	public function testCoversNothingTag() {}

	/**
	 * Docblock.
	 *
	 * @coversNothing
	 * @covers ::globalFunction
	 */
	public function testCoversNothingAndCoversTag() {}

	/**
	 * Docblock.
	 *
	 * @covers ::globalFunction
	 */
	public function testHasCoversTag() {}

	/**
	 * Docblock.
	 *
	 * @test
	 */
	public static function annotatedTestMissingCoversTag() {}

	/**
	 * Docblock.
	 *
	 * @test
	 * @coversNothing
	 */
	public function annotatedTestCoversNothingTag() {}

	/**
	 * Docblock.
	 *
	 * @test
	 * @covers ::globalFunction
	 */
	final public function annotatedTestHasCoversTag() {}
}

/**
 * @covers ClassName
 */
#[Some_Attribute]
final class ClassLevelCoversWithAttributeTest {

	/**
	 * Docblock.
	 */
	public function testMissingCoversTag() {}
}

class ClassWithMethodsWithAttributesTest {

	/**
	 * Docblock.
	 */
	#[AttributeA]
	#[AttributeB]
	public function testMissingCoversTag() {}

	/**
	 * Docblock.
	 *
	 * @covers ::globalFunction
	 */
	#[AttributeA]
	public function testHasCoversTag() {}
}

class TestPrefixNotSuffix {

	/**
	 * Docblock.
	 */
	function testSomething() {}
}

abstract class ContainsTestMethodsTestCase {

	/**
	 * Docblock - this concrete test should have a covers tag.
	 */
	function testSomething() {}

	/**
	 * Docblock - this abstract method can be ignored.
	 */
	abstract function testToBeImplementedInChildClass();
}

/**
 * Docblock.
 *
 * @sometag
 * @anothertag
 */
class DoesNotHaveOurTargetTagsTest {
	/**
	 * Docblock with tags, but not any of the tags we're looking for.
	 *
	 * @param mixed  $input
	 * @param string $expected
	 *
	 * @return void
	 */
	final public function testSomething($input, $expected) {}
}

/**
 * Global functions should be ignored as PHPUnit tests should always be in a class.
 */
function testMe() {}

