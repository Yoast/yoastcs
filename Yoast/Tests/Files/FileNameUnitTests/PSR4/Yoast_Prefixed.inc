<!-- Annotation must be on line 2 as this sniff throws issues on line 1 and PHPCS ignores errors on annotation lines. -->
phpcs:set Yoast.Files.FileName psr4_paths[] PrefixA=>/src/,PrefixB=>/PSR4/
phpcs:set Yoast.Files.FileName oo_prefixes[] wpseo,yoast

<?php

// For PSR4 file names, prefixes should *not* be stripped.
class Yoast_Prefixed {}

// phpcs:set Yoast.Files.FileName psr4_paths[]
// phpcs:set Yoast.Files.FileName oo_prefixes[]
