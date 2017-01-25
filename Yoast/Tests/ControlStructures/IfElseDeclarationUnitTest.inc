<?php

// Ok.
if ( true ) {
	// code.
}
else {
	// ...
}

if ( false ):
else:
endif;


// Bad.
if ( true ) {
	// code.
} else { // Bad - else should be on the next line.
	// ...
}

if ( true ) {
	// code.
} elseif( 1 ) { // Bad - elseif should be on the next line.
	// ...
} elseif( 2 ) { // Bad - elseif should be on the next line.
	// ...
} elseif( 3 ) { // Bad - elseif should be on the next line.
	// ...
}
