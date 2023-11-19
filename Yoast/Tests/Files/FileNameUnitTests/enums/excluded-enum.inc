<!-- Annotation must be on line 2 as this sniff throws issues on line 1 and PHPCS ignores errors on annotation lines. -->
phpcs:set Yoast.Files.FileName excluded_files_strict_check[] enums/excluded-enum.inc

<?php

enum My_Enum: int {}

// phpcs:set Yoast.Files.FileName excluded_files_strict_check[]
