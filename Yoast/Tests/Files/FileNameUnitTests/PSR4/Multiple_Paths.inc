<!-- Annotation must be on line 2 as this sniff throws issues on line 1 and PHPCS ignores errors on annotation lines. -->
phpcs:set Yoast.Files.FileName psr4_paths[] PrefixA\=>Other_Path/,PrefixB=>/PSR4/,PrefixC\=>/Deep/Path/Sub/

<?php

class Multiple_Paths {}

// phpcs:set Yoast.Files.FileName psr4_paths[]
