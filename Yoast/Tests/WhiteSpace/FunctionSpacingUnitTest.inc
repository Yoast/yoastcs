<?php

/**
 * Comment
 */
function foo()
{
}




function func1() {

}//end func1()
function func2() {

}

if ( ! function_exists( 'func3' ) ) {
	/**
	 * Docblock.
	 */
	function func3() {
	}
}


class MyClass
{
	function func1() {

	}//end func1()



	function func2() {

	}
	function func3() {

	}

	function func4() {

	}

}


interface MyInterface
{
	function func1();



	function func2();
	function func3();

	function func4();

}


trait MyTrait
{

	function func1() {

	}//end func1()

	function func2() {

	}


	function func3() {

	}

	function func4() {

	}
}

$util->setLogger(new class {
	public function a(){}
	private function b(){}
	protected function c(){}
});

enum MyTrait: int
{
	function func1() {

	}//end func1()



	function func3() {

	}

}
