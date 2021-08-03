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
class ClassLevelCoversNothingTest {

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
	public function annotatedTestMissingCoversTag() {}

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
	public function annotatedTestHasCoversTag() {}
}

/**
 * @covers ClassName
 */
#[Some_Attribute]
class ClassLevelCoversWithAttributeTest {

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
