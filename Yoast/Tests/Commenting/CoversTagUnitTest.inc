<?php

class ClassNameTest {

	/**
	 * Docblock.
	 *
	 * Correct:
	 * @covers ::global_function
	 * @covers Name\Space\function_name
	 * @covers \Name\Space\function_name
	 * @covers Class_Name
	 * @covers Class_Name<extended>
	 * @covers Class_Name::<public>
	 * @covers Class_Name::<protected>
	 * @covers Class_Name::<private>
	 * @covers Class_Name::<!public>
	 * @covers Class_Name::<!protected>
	 * @covers Class_Name::<!private>
	 * @covers Name\Space\Class_Name
	 * @covers \Name\Space\Class_Name
	 * @covers Name\Space\Class_Name::<public>
	 * @covers Name\Space\Class_Name::<protected>
	 * @covers Name\Space\Class_Name::<private>
	 * @covers Name\Space\Class_Name::<!public>
	 * @covers Name\Space\Class_Name::<!protected>
	 * @covers Name\Space\Class_Name::<!private>
	 * @covers Class_Name::method_name
	 * @covers \Class_name::method_name
	 * @covers Name\Space\Class_Name::method_name
	 * @covers \Name\Space\Class_name::method_name
	 * @covers global_function
	 * @covers self::method_name
	 *
	 * Incorrect:
	 * @covers ::global_func()
	 * @covers Name\Space\func_name()
	 * @covers My_Class {}
	 * @covers My_Class::another_method_name()
	 * @covers Name\Space\My_Class::another_method_name()
	 */
	public function testCoversTag() {}

	/**
	 * Docblock.
	 *
	 * @covers ::global_functionA && ::other_functionA
	 * @covers ::global_functionB & ::other_functionB
	 * @covers ::global_functionC|::other_functionC
	 * @covers ::global_functionD||::other_functionD & Some_Class_Name
	 */
	public function testCoversTagSplitUnionIntersect() {}

	/**
	 * Docblock.
	 *
	 * @covers Class_Name::public
	 * @covers Class_Name::protected
	 * @covers Class_Name::private
	 */
	public function testCoversTagFixGroupsTypeA() {}

	/**
	 * Docblock.
	 *
	 * @covers Class_Name::!public
	 * @covers Class_Name::!protected
	 * @covers Class_Name::!private
	 */
	public function testCoversTagFixGroupsTypeB() {}

	/**
	 * Docblock.
	 *
	 * @covers Class_Name::{public}
	 * @covers Class_Name::(!protected)
	 * @covers Class_Name::[private]
	 */
	public function testCoversTagFixGroupsTypeC() {}

	/**
	 * Docblock.
	 *
	 * @covers
	 */
	public function testHasCoversTagNoContent() {}

	/**
	 * Docblock.
	 *
	 * @covers
	 * Some unrelated comment.
	 */
	public function testHasCoversTagNoContentOnSameLine() {}

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
	 * @coversNothing
	 * @since x.x.x
	 *
	 * @coversNothing
	 * @param int $int Description.
	 *
	 * @coversNothing
	 */
	public function testDuplicateCoversNothingTagFixable($int) {}

	/**
	 * Docblock.
	 *
	 * @coversNothing
	 * @since x.x.x
	 *
	 * @coversNothing Some comment.
	 * @param int $int Description.
	 *
	 * @coversNothing
	 */
	public function testDuplicateCoversNothingTagWithCommentFixable($int) {}

	/**
	 * Docblock.
	 *
	 * @coversNothing
	 * @since x.x.x
	 *
	 * @coversNothing Some comment.
	 * @param int $int Description.
	 *
	 * @coversNothing Another comment.
	 */
	public function testDuplicateCoversNothingTagUnfixable($int) {}

	/**
	 * Docblock.
	 *
	 * @since x.x.x
	 *
	 * @covers Name\Space\function_name
	 * @covers Class_Name
	 * @covers Name\Space\function_name
	 * @covers Name\Space\function_name
	 *
	 * @param int $int Description.
	 */
	public function testDuplicateCoversTagFixable($int) {}

	/**
	 * Docblock.
	 *
	 * Correct:
	 * @covers ::GlobalFunction
	 * @covers ClassName::MethodName
	 * @covers \Classname::MethodName
	 * @covers \Name\Space\ClassName::MethodName
	 */
	public function testRecognizingNamesNotFollowingWPNamingConventions() {}

	/**
	 * Docblock.
	 */
	public function testDocblockWithoutTags() {}

	/**
	 * Docblock.
	 *
	 * @covers This is not a valid annotation.
	 * @covers \
	 * @covers <public>
	 */
	public function testCompletelyInvalidAnnotations() {}
}

/**
 * The <*> formats can also be used in combination with a `@coversDefaultClass` tag.
 *
 * @coversDefaultClass Class_Name
 */
class BracketedPartialsTest {
	/**
	 * @covers ::<public>
	 * @covers ::<protected>
	 * @covers ::<private>
	 * @covers ::<!public>
	 * @covers ::<!protected>
	 * @covers ::<!private>
	 */
	public function testPartialTagTypes() {}
}
