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
elseif: // Intentional parse error.
else if: // Intentional parse error.
else /* comment */
if: // Intentional parse error.
else: // Intentional parse error.
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
} else { // Bad - else should be on the next line.
	// ...
}

if ( true ) {
	// code.
}
	else { // Bad - else not aligned with if.
	// ...
	}

				if ( true ) {
					// code.
				}
	else { // Bad - else not aligned with if.
	// ...
	}

if ( true ) {
	// code.
}


else { // OK - multiple blank lines between if/else are ignored, not the concern of this sniff.
	// ...
}

if ( true ) {
	// code.
}
// Some comment - this comment should be ignored.
else {
	// ...
}

if ( true ) {
	// code.
}
	/* phpcs:ignore Standard.Category.Sniffname -- for reasons. */
	else { // Bad - else not aligned with if.
		// ...
	}

if ( true ) {
	// code.
}
?> <!-- This would be a parse error anyway. --> <?php
	else { // Bad.
		// ...
	}
