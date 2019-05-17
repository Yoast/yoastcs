<!-- Annotation must be on line 2 as this sniff throws issues on line 1 and PHPCS ignores errors on annotation lines. -->
phpcs:set Yoast.Files.FileName exclude[] traits/excluded-trait-file.inc

<?php

trait My_Trait {}

// phpcs:set Yoast.Files.FileName exclude[]
