<?php

use Brain\Monkey;
use Brain\Monkey\Functions;
use Brain\Monkey\Filters;
use Brain\Monkey\Actions;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Actions\expectDone;

// Code outside of a class method should be disregarded.
Functions\expect( "apply_filters" )->once();

if ($something) {
    Functions\expect( "apply_filters" )->once();
	Filters\ExpectApplied( 'filter_name' )
		->with( true );
}

function NOT_race_condition_as_outside_class() {
	Functions\expect( "apply_filters" )->once();

	Filters\ExpectApplied( 'filter_name' )
		->with( true );

	// Do something to satisfy expectations.
}

class Expect implements Something {}

class BrainMonkeyBasedTest extends TestCase {

	// Not a function call.
	const EXPECT = 'something';

	// Function declaration, not call.
	function expect() {}

	public function test_NOT_race_condition_not_the_expect_we_are_looking_for() {
		// Static method call.
		My::expect( 'apply_filters' )->once();
		parent::expect( 'do_action' )->once();

		// Method call.
		$obj->expect( 'apply_filters' )->once();
		$obj->something()?->expect( 'apply_filters' )->once();

		// Missing param.
		expect()->once();

		// Param dynamic.
		Functions\expect( $function )->once();

		// Param partially dynamic.
		Functions\expect( 'apply_filters' . $extra )->once();
	}

	public function test_NOT_race_condition_expect_not_called_with_hook_functions() {
		Functions\expect( 'some_other_function' )->once();
		expect( 'yet_another_function' )->once();

		Filters\expectApplied('filter_name')
			->with( true );

		Monkey\Actions\expectDone( 'yoast\action_name' )
			->with( $param );

		// Do something to satisfy expectations.
	}

	public function test_NOT_race_condition_expectapplieddone_not_found_as_function_calls() {
		Functions\expect( 'apply_filters' )->once();
		expect( 'do_action' )->once();

		// Not a function call.
		Filters\expectApplied;
		echo expectDone;

		// Do something to satisfy expectations.
	}

	public function test_NOT_race_condition_only_expect() {
		expect( 'apply_filters' )->once();
		Functions\expect( "apply_filters" )->once();
		Monkey\Functions\expect( 'do_action' )->once();

		// Do something to satisfy expectations.
	}

	public function test_NOT_race_condition_only_expectApplied_expectDone() {
		Monkey\Filters\expectApplied( 'filter_name' )
			->with( true );

		Actions\expectDone( "yoast\action_name" )
			->with( $param );

		expectDone( 'yoast\action_name' )
			->with( $param );

		// Do something to satisfy expectations.
	}

	public function test_filter_race_condition() {
		Functions\expect  ("apply_filters")->once();

		Filters\ExpectApplied  ( 'filter_name' )
			->with( true );

		// Do something to satisfy expectations.
	}

	public function test_action_race_condition() {
		Expect( 'do_action' /*comment*/)->once();

		Actions\expectDone( 'yoast\action_name' )
			->with( $param );

		// Do something to satisfy expectations.
	}

	public function test_filter_race_condition_order_reversed() {
		expectApplied( /*comment*/ 'filter_name' )
			->with( true );

		Monkey\Functions\expect( 'apply_filters' /*comment*/)->once();

		// Do something to satisfy expectations.
	}

	public function test_action_race_condition_order_reversed() {
		Monkey\Actions\expectDone( 'action_name' )
			->with( $param );

		Monkey\Functions\expect( 'do_action' )->once();

		// Do something to satisfy expectations.
	}
}
