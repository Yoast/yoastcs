<!-- Annotation must be on line 2 as this sniff throws issues on line 1 and PHPCS ignores errors on annotation lines. -->
phpcs:set Yoast.Files.FileName psr4_paths[] Prefix=>/PSR4/
phpcs:set Yoast.Files.FileName excluded_files_strict_check[] PSR4/Not_Excluded.inc

<?php

// The excluded files property should only be applied when prefix stripping is supported, which it is not for PSR4 files.
class Yoast_Not_Excluded {}

// phpcs:set Yoast.Files.FileName psr4_paths[]
// phpcs:set Yoast.Files.FileName excluded_files_strict_check[]
