<!-- Annotation must be on line 2 as this sniff throws issues on line 1 and PHPCS ignores errors on annotation lines. -->
phpcs:set Yoast.Files.FileName psr4_paths[] Prefix\\=>/PSR4/

<?php

// Files without an OO structure, should follow the normal "lowercase hyphenated" + "-functions" suffix file name rules.
function Foo() {}

function Bar() {}

// phpcs:set Yoast.Files.FileName psr4_paths[]
