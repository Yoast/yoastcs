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
