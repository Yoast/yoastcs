<?php

namespace;

/**
 * Class docblock. A namespace keyword without actual namespace defaults to global namespace, so a file comment is needed, but missing.
 *
 * Note: an unscoped namespace declaration without a name is a parse error, but that's not the concern of this sniff.
 */
class Testing {}
