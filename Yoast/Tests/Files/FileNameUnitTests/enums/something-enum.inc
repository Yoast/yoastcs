<!-- Annotation must be on line 2 as this sniff throws issues on line 1 and PHPCS ignores errors on annotation lines. -->
phpcs:set Yoast.Files.FileName oo_prefixes[] wpseo,yoast

<?php

enum Yoast_Something: string {}

// phpcs:set Yoast.Files.FileName oo_prefixes[]
