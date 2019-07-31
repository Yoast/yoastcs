<?php

namespace; // Error - no name. (Intentional parse error)

namespace {} // Error x 3 - no name + scoped namespace declaration + not the only namespace declaration in the file.

namespace Yoast\SecondScopedNamespace {} // Error x 2 - scoped namespace declaration + not the only namespace declaration in the file.

namespace /* some comment */ {} // Error x 3 - no name + scoped namespace declaration + not the only namespace declaration in the file.

namespace /* some comment */; // Error x 2 - no name + not the only namespace declaration in the file. (Intentional parse error)
