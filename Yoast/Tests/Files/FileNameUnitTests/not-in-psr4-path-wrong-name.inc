<!-- Annotation must be on line 2 as this sniff throws issues on line 1 and PHPCS ignores errors on annotation lines. -->
phpcs:set Yoast.Files.FileName psr4_paths[] Prefix\\=>/PSR4/

<?php

class Not_In_PSR4_Path {}

// phpcs:set Yoast.Files.FileName psr4_paths[]
