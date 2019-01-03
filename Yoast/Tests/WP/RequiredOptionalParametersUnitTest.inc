<?php

// Missing autoload option.
update_option( 'a', 'b' );

// Ok.
update_option( 'a', 'b', true );

// Null triggers an error, as it is not explicit.
update_option( 'a', 'b', null );

// Missing autoload option.
add_option( 'a', 'b' );

// Missing autoload option.
add_option( 'a', 'b', '' );

// Ok.
add_option( 'a', 'b', '', 'yes' );

// Null triggers an errors, as it is not explicit.
add_option( 'a', 'b', '', null );

add_option(
	'a',
	'b',
	'',
	// Testing comments.
	null // phpcs:ignore Standard.Cat.SniffName -- testing comment ignoring
);
