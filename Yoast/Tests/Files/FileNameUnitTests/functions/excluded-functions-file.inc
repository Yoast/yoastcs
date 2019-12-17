<!-- Annotation must be on line 2 as this sniff throws issues on line 1 and PHPCS ignores errors on annotation lines. -->
phpcs:set Yoast.Files.FileName excluded_files_strict_check[] functions/excluded-functions-file.inc

<?php

function some_function() {}

// phpcs:set Yoast.Files.FileName excluded_files_strict_check[]