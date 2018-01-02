<?php

// Ok.
if ( true ) {
	// code.
}
else {
	// ...
}

// Ok.
if ( false ):
elseif:
else if:
else /* comment */
if:
else:
endif;

if ( true ) {
	// code.
} else { // Bad - else should be on the next line.
	// ...
}

if ( true ) {
	// code.
} elseif( 1 ) { // Bad - elseif should be on the next line.
	// ...
} else if       (2 // Bad - else if should be on the next line.
	|| 6
) {
	// ...
} else // Bad - else if should be on the next line.
if (2) {
	// ...
} else ( 3 ) { // Bad - else should be on the next line.
	// ...
}
