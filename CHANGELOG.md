# Changelog for YoastCS

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/) and [Keep a CHANGELOG](https://keepachangelog.com/).

### [3.4.0] - 2026-02-20

#### Changed
* Composer: Supported version of [PHPCompatibilityWP] has been changed from `^2.1.6` to `^3.0.0@alpha`.
    This allows for installing the much, much improved PHPCompatibility 10.0.0 (prerelease).
    To do so, the `composer.json` file of packages requiring this package must have `"minimum-stability": "dev"` or `"alpha"` (or other tweaks which net the same effect).


### [3.3.0] - 2026-02-04

#### Added
* PHPCS: The `SlevomatCodingStandard.Arrays.ArrayAccess` sniff.
* PHPCS: The `SlevomatCodingStandard.ControlStructures.RequireNullCoalesceOperator` sniff.
* PHPCS: The `SlevomatCodingStandard.ControlStructures.RequireNullCoalesceEqualOperator` sniff.
* PHPCS: The `SlevomatCodingStandard.Functions.RequireTrailingCommaInCall` sniff.
* PHPCS: The `SlevomatCodingStandard.Functions.DisallowTrailingCommaInCall` sniff (for single-line function calls).
* PHPCS: The `SlevomatCodingStandard.Numbers.RequireNumericLiteralSeparator` sniff.
* PHPCS: The `Universal.Attributes.TrailingComma` sniff.
* PHPCS: The `Universal.Attributes.BracketSpacing` sniff (via WordPressCS).
* PHPCS: The `Universal.Attributes.DisallowAttributeParentheses` sniff (via WordPressCS).
* PHPCS: The `Universal.PHP.NoFQNTrueFalseNull` sniff.
* PHPCS: The `Universal.PHP.RequireExitDieParentheses` sniff.
* PHPCS: The `WordPress.WP.GetMetaSingle` sniff (via WordPressCS).

#### Changed
* Composer: Supported version of [PHPCSExtra] has been changed from `^1.2.1` to `^1.5.0`.
* Composer: Supported version of [VariableAnalysis] has been changed from `^2.12.0` to `^2.13.0`.
* Composer: Supported version of [WordPressCS] has been changed from `^3.1.0` to `^3.3.0`.
* PHPCS: The default value for the `minimum_wp_version` property which is used by various WPCS sniffs has been updated to WP `6.8` (was `6.6`).
* Various housekeeping.


### [3.2.0] - 2025-03-19

#### Added
* PHPCS: The `Generic.Strings.UnnecessaryHeredoc` sniff.
* PHPCS: The `Generic.WhiteSpace.HereNowdocIdentifierSpacing` sniff.

#### Changed
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.9.1` to `^3.12.0`.
* Composer: Supported version of [PHPCSUtils] has been changed from `^1.0.10` to `^1.0.12`.
* Composer: Supported version of [PHPCompatibilityWP] has been changed from `^2.1.4` to `^2.1.6`.
* Composer: Supported version of [VariableAnalysis] has been changed from `^2.11.17` to `^2.12.0`.
* Composer: Supported version of [WordPressVIP Coding Standards] has been changed from `^3.0.0` to `^3.0.1`.
* Composer: Updated the supported versions of dev dependencies.
* PHPCS: The default setting for the minimum supported PHP version for repos using YoastCS is now PHP `7.4` (was `7.2`).
* PHPCS: The default value for the `minimum_wp_version` property which is used by various WPCS sniffs has been updated to WP `6.6` (was `6.3`).
* PHPCS: verified runtime compatibility with PHP 8.4.
* PHPCS: compatibility with [SlevomatCodingStandard] 8.16.0.
* Various housekeeping.

#### Fixed
* PHPCS: The spacing around comma's in short arrays nested in function calls will now be flagged correctly again.

### [3.1.0] - 2024-04-05

#### Added

* Threshold report: aside from the pre-existing `YOASTCS_ABOVE_THRESHOLD` constant, a new `YOASTCS_THRESHOLD_EXACT_MATCH` constant will now be available to determine (from a calling script) whether the error/warning thresholds were matched exactly or need to be updated.

#### Changed
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.8.0` to `^3.9.1`.
* Composer: Supported version of [PHPCSUtils] has been changed from `^1.0.9` to `^1.0.10`.
* Composer: Supported version of [WordPressCS] has been changed from `^3.0.1` to `^3.1.0`.
    This introduces one new sniff - `Generic.CodeAnalysis.RequireExplicitBooleanOperatorPrecedence` - to the YoastCS ruleset.
* Composer: Supported version of [SlevomatCodingStandard] has been changed from `^8.14.0` to `^8.15.0`.
* Composer: Supported version of [PHP Parallel Lint] has been changed from `^1.3.2` to `^1.4.0`.
* PHPCS: The default value for the `minimum_wp_version` (previously `minimum_supported_wp_version`) property which is used by various WPCS sniffs has been updated to WP `6.3` (was `6.2`).
* Various housekeeping.


### [3.0.0] - 2023-12-14

#### Added
* Composer/PHPCS: Dependencies on the following external PHPCS standards packages: [PHPCSUtils], [PHPCSExtra], [SlevomatCodingStandard], [VariableAnalysis] and [WordPressVIP Coding Standards].
* PHPCS: A best effort has been made to add support for new PHP syntaxes/features to all YoastCS native sniffs and utility functions (or to verify/improve existing support).
    YoastCS native sniffs and utilities have received fixes for the following syntaxes:
    * PHP 5.6
        - Parameter unpacking in function calls.
    * PHP 8.0
        - Named arguments in function calls.
    * PHP 8.1
        - Enumerations.
        - First class callables.
    * PHP 8.2
        - Readonly classes.
* PHPCS: The `Yoast.Commenting.CoversTag` sniff includes a new warning for the use of `ClassName<*>` type `@covers` annotations, as these have been deprecated as of PHPUnit 9.0.
* PHPCS: The `Yoast.Files.FileName` sniff now has the (optional) ability to check whether file names comply with PSR-4.
    To enable this ability, add the custom `psr4_paths` property to your ruleset. The `psr4_paths` property is an array property and mirrors the `psr4` setting in the Composer `autoload` directive. It expects a namespace prefix as the array key and a comma separated list of relative paths as the array value. Multiple PSR-4 paths can be passed (array elements).
    For files containing OO structures in a "PSR4 path", the `oo_prefixes` and the `excluded_files_strict_check` properties will be ignored.
* PHPCS: The `Yoast.NamingConventions.NamespaceName` sniff will now throw a `MissingPrefix` error if a prefix is expected, but the namespace name does not include a prefix.
* PHPCS: The `Yoast.NamingConventions.NamespaceName` sniff will now throw a `DirectoryInvalid` error if a file is in a directory which would not result in a valid namespace name.
* PHPCS: The `Yoast.NamingConventions.NamespaceName` sniff now has the (optional) ability to check whether namespace names comply with PSR-4.
    To enable this ability, add the custom `psr4_paths` property to your ruleset. The `psr4_paths` property is an array property and mirrors the `psr4` setting in the Composer `autoload` directive. It expects a namespace prefix as the array key and a comma separated list of relative paths as the array value. Multiple PSR-4 paths can be passed (array elements).
    A `psr4_paths` property will take precedence over the, potentially set, `src_directory` and `prefixes` properties.
* PHPCS: The following sniffs/error codes have been added to/enabled in the YoastCS ruleset (with appropriate configuration):
    - All new sniffs which were added/included in [WordPressCS 3.0.0](https://github.com/WordPress/WordPress-Coding-Standards/releases/tag/3.0.0).
    - `PSR1.Classes.ClassDeclaration` (for tests only)
    - `PSR12.Properties.ConstantVisibility`
    - `SlevomatCodingStandard.Arrays.DisallowImplicitArrayCreation`
    - `SlevomatCodingStandard.Classes.ClassStructure`
    - `SlevomatCodingStandard.Classes.ModernClassNameReference`
    - `SlevomatCodingStandard.Functions.StaticClosure`
    - `SlevomatCodingStandard.Namespaces.AlphabeticallySortedUses`
    - `SlevomatCodingStandard.Namespaces.FullyQualifiedGlobalConstants`
    - `SlevomatCodingStandard.Namespaces.FullyQualifiedGlobalFunctions`
    - `SlevomatCodingStandard.Namespaces.ReferenceUsedNamesOnly`
    - `SlevomatCodingStandard.Namespaces.UnusedUses`
    - `SlevomatCodingStandard.Namespaces.UseFromSameNamespace`
    - `SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint` (tests excluded)
    - `SlevomatCodingStandard.TypeHints.LongTypeHints`
    - `SlevomatCodingStandard.TypeHints.NullableTypeForNullDefaultValue`
    - `SlevomatCodingStandard.TypeHints.NullTypeHintOnLastPosition`
    - `SlevomatCodingStandard.TypeHints.ParameterTypeHint`
    - `SlevomatCodingStandard.TypeHints.PropertyTypeHint`
    - `SlevomatCodingStandard.TypeHints.ReturnTypeHint`
    - `Squiz.Commenting.FunctionComment.InvalidReturnNotVoid`
    - `Squiz.Commenting.FunctionComment.MissingReturn`
    - `Squiz.Commenting.FunctionComment.ParamCommentNotCapital`
    - `Squiz.Commenting.FunctionComment.SpacingAfterParamName`
    - `Squiz.WhiteSpace.SuperfluousWhitespace.EmptyLines`
    - `Universal.Classes.RequireFinalClass` (for tests only, doubles/mocks excluded)
    - `Universal.CodeAnalysis.NoDoubleNegative`
    - `Universal.ControlStructures.DisallowAlternativeSyntax`
    - `Universal.ControlStructures.IfElseDeclaration`
    - `Universal.FunctionDeclarations.NoLongClosures`
    - `Universal.Operators.ConcatPosition`
    - `Universal.Operators.DisallowLogicalAndOr`
    - `Universal.PHP.LowercasePHPTag`
    - `Universal.UseStatements.DisallowUseConst`
    - `Universal.UseStatements.DisallowUseFunction`
    - `VariableAnalysis.CodeAnalysis.VariableAnalysis`
    - `WordPressVIPMinimum.Classes.DeclarationCompatibility`
    - `WordPressVIPMinimum.Hooks.AlwaysReturnInFilter`
    - `WordPressVIPMinimum.Security.EscapingVoidReturnFunctions`
    - `WordPressVIPMinimum.Security.ProperEscapingFunction`
* PHPCS: New `PathHelper`, `PathValidationHelper` and `PSR4PathsTraits` classes/traits for use by the sniffs.
* Readme: section on the YoastCS `Threshold` report.

#### Changed
* :warning: The minimum supported PHP version for this package is now PHP 7.2 (was 5.4).
* PHPCS: all sniffs are now runtime compatible with PHP 7.2 - 8.3.
* :warning: PHPCS: All non-abstract classes in YoastCS are now `final` and all non-`public` methods and properties are now `private`.
    Additionally, all non-private methods in traits have also been made `final`.
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.7.1` to `^3.8.0`.
* :warning: Composer: Supported version of [WordPressCS] has been changed from `^2.3.0` to `^3.0.1`.
    YoastCS is now fully compatible with WordPressCS 3.0.
    Note: WordPressCS 3.0.0 contains breaking changes. Please read the [WordPressCS release announcement](https://make.wordpress.org/core/2023/08/21/wordpresscs-3-0-0-is-now-available/) and follow the [WordPressCS upgrade guides](https://github.com/WordPress/WordPress-Coding-Standards/wiki/).
* PHPCS: The default value for the `minimum_wp_version` (previously `minimum_supported_wp_version`) property which is used by various WPCS sniffs has been updated to WP `6.2` (was `6.0`).
* PHPCS: Files in a `wp-content/plugins/` subdirectory will now always be ignored for PHPCS scans.
* PHPCS: The ruleset included value for the `doubles_path` property in the `Yoast.Files.TestDoubles` sniff has been updated to include the typical Yoast `test/Unit/Doubles` and `test/WP/Doubles` directories as per the restructured tests.
* PHPCS: The `Yoast.Commenting.CodeCoverageIgnoreDeprecated` sniff will now also examine class docblocks.
* PHPCS: The `Yoast.Commenting.FileComment` sniff will no longer flag a file docblock in a namespaced file which doesn't contain an OO structure as redundant.
* PHPCS: The `Yoast.Files.FileName` sniff will now also examine the file name of PHP files using only the PHP short open tag (`<?=`).
* PHPCS: The `Yoast.Files.FileName` sniff will now respect inline PHPCS native ignore/disable annotations for the sniff.
    Note: the annotations are respected for the sniff itself, but not for individual error codes from the sniff!
* PHPCS: The `Yoast.Files.FileName` sniff now recognizes more word separators, meaning that files using other word separators than underscores will now be flagged for not using hyphenation.
* PHPCS: The `Yoast.Files.FileName` sniff will now throw a potential "missing basepath" warning only once per run, not for each file triggering the sniff.
* PHPCS: The default value for the `doubles_path` property in the `Yoast.Files.TestDoubles` sniff is now an empty array.
    This property should now always be set in a custom ruleset.
    Note: the YoastCS ruleset sets this property, so if the ruleset is used instead of explicitly including the individual sniff, this change has no impact.
* PHPCS: The "namespace level depth" check in the `Yoast.NamingConventions.NamespaceName` sniff now allows for test fixtures directories being at a deeper level than directly under a `tests` directory.
* PHPCS: The `Yoast.NamingConventions.ObjectNameDepth` sniff now allows for test classes using a `_TestCase` suffix and will allow for extra object name depth for those, same as for test classes with a `_Test` suffix.
* :warning: PHPCS: The `Yoast.Yoast.AlternativeFunctions` sniff has been renamed to `Yoast.Yoast.JsonEncodeAlternative`.
* The `Yoast.Yoast.JsonEncodeAlternative` sniff (previously `Yoast.Yoast.AlternativeFunctions`) is now disabled by default for files in a `/tests/` directory.
* The `Generic.CodeAnalysis.UselessOverridingMethod` sniff is now disabled by default for files in a `/tests/*/Doubles/` directory.
* The `WordPress.WP.GlobalVariablesOverride` sniff is now disabled by default for files in a `/tests/` directory.
* PHPCS: The eror messages for the `Yoast.Files.TestDoubles` sniff have been improved for accuracy.
* PHPCS: The error message for the `Yoast.NamingConventions.NamespaceName.Invalid` error has been made more informative/actionable.
* PHPCS: The error message for the `Yoast.NamingConventions.ValidHookName.NonString` warning has been made more actionable.
* PHPCS: Various sniffs have received efficiency fixes for improved performance.
* PHPCS: the XML documentation for various sniffs has been updated and improved.
* Various housekeeping.

#### Removed
* PHPCS: The `Yoast.ControlStructures.IfElseDeclaration` sniff (replaced by the `Universal.ControlStructures.IfElseDeclaration` sniff from PHPCSExtra).
* PHPCS: The `Yoast.Namespaces.NamespaceDeclaration` sniff (replaced by a variety of sniffs from PHPCSExtra).
* PHPCS: Various explicit rule inclusions for sniffs which are now inherited from WordPressCS 3.0.
* PHPCS: Support for the `Yoast.Files.TestDoubles` `doubles_path` property being passed as a string instead of an array.
    Passing the property as a string was previously deprecated in YoastCS 1.1.0.
* PHPCS: The `Yoast.Files.TestDoubles.OneObjectPerFile` error code.
    This is already checked via the `Generic.Files.OneObjectStructurePerFile` sniff, which is included in WordPressCS, anyway.

#### Fixed
* PHPCS: Various sniffs now include more defensive coding to prevent potential PHP notices/errors, particularly when running the sniffs during live coding in an IDE.
* PHPCS: Various sniffs have received fixes for improved handling of directory path and file names passed through custom properties.
* PHPCS: The `Yoast.Commenting.CoversTag` sniff will no longer flag `::<[!]visibility>` annotations as an invalid format when combined with a `@coversDefaultClass` tag.
* PHPCS: The `Yoast.Commenting.TestHaveCoversTag` sniff will no longer examine global functions.
* PHPCS: The `Yoast.Files.FileName` sniff will now handle the values for the `excluded_files_strict_check` property in a case-sensitive manner (as file names are case-sensitive on most operating systems).
* PHPCS: The `Yoast.Files.TestDoubles` sniff will now handle the values for the `doubles_path` property in a case-sensitive manner (as directory names are case-sensitive on most operating systems).
* PHPCS: The `Yoast.NamingConventions.NamespaceName` sniff will now bow out earlier if the namespace name is invalid (parse error).
* PHPCS: The `Yoast.NamingConventions.NamespaceName` sniff will now handle "directory to namespace name" translations more accurately and will no longer throw an error if the directory name contains an underscore.
* PHPCS: The `Yoast.NamingConventions.ObjectNameDepth` sniff now has a more accurate object name depth calculation for OO structures with a name in `CamelCaps`.
    This should prevent various false positives for test classes/test doubles.
* PHPCS: The `Yoast.NamingConventions.ObjectNameDepth` sniff will no longer check if a class extends a known "TestCase" to determine whether to allow for extra object name depth, it will just base itself on the name of the object under examination, which should prevent some false positives.
* PHPCS: The fixer in the `Yoast.Yoast.JsonEncodeAlternative` sniff (previously `Yoast.Yoast.AlternativeFunctions`) will no longer inadvertently create a parse error when fixing fully qualified function calls.
* PHPCS: The `Yoast.Yoast.JsonEncodeAlternative` sniff (previously `Yoast.Yoast.AlternativeFunctions`) will now no longer try to auto-fix when it encounters PHP 5.6+ parameter unpacking.
* PHPCS: The `Yoast.Yoast.JsonEncodeAlternative` sniff (previously `Yoast.Yoast.AlternativeFunctions`) will now no longer try to auto-fix when it encounters a PHP 8.1+ first class callable.

### [2.3.1] - 2023-03-09

#### Changed
* PHPCS: The default setting for the minimum supported PHP version for repos using YoastCS is now PHP 7.2 (was 5.6).
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.7.1` to `^3.7.2`.

### [2.3.0] - 2023-01-09

#### Added
* PHPCS: New `Yoast.Tools.BrainMonkeyRaceCondition` sniff.
    This sniff will flag test code which is likely to run into a particular race condition when using BrainMonkey to set expectations for WP hook functions.

#### Changed
* PHPCS: The default value for the `minimum_supported_wp_version` property which is used by various WPCS sniffs has been updated to WP `6.0` (was `5.7`).
* Composer: The package will now identify itself as a static analysis tool. Thanks [@GaryJones]!
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.6.2` to `^3.7.1`.
* Composer: Supported version of [PHPCompatibilityWP] has been changed from `^2.1.3` to `^2.1.4`.
* Composer: Supported version of the [Composer PHPCS plugin] has been changed from `^0.5 || ^0.6.2 || ^0.7` to `^0.6.2 || ^0.7 || ^1.0`.
* Various housekeeping.

#### Fixed
* PHPCS: `Yoast.Files.FileComment`: the sniff will now allow for and respect `phpcs:disable` comments for this sniff when found in a file docblock.

### [2.2.1] - 2022-02-22

#### Changed
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.6.0` to `^3.6.2`.
* Composer: Supported version of [PHPCompatibilityWP] has been changed from `^2.1.0` to `^2.1.3`.
* Composer: Supported version of [PHP Parallel Lint] has been changed from `^1.3.1` to `^1.3.2`.
* Composer: Supported version of [PHP Console Highlighter] has been changed from `^0.5.0` to `^1.0.0`.
* Readme: Updated installation instructions for compatibility with Composer 2.2+ and other minor improvements.
* Various housekeeping.


### [2.2.0] - 2021-09-22

#### Added
* [PHP Parallel Lint] will now be provided via YoastCS.
    If `php-parallel-lint/php-parallel-lint` and `php-parallel-lint/php-console-highlighter` are included in the `require-dev` of your `composer.json` file, you can remove these after updating the version constraint for YoastCS to `"yoast/yoastcs": "^2.2.0"`.
* PHPCS: A custom `YoastCS\Yoast\Reports\Threshold` report.
    This commit adds a custom report for use with PHPCS to compare the run results with "threshold" settings.
    - The report will look in the runtime environment for the following two environment variables and will take the values of those as the thresholds to compare the PHPCS run results against:
        * `YOASTCS_THRESHOLD_ERRORS`
        * `YOASTCS_THRESHOLD_WARNINGS`
    - If the environment variables are not set, they will default to 0 for both, i.e. no errors or warnings allowed.
    - After the report has run, a global `YOASTCS_ABOVE_THRESHOLD` constant (boolean) will be available which can be used in calling scripts.
    - To use this report, run PHPCS with the following command-line argument: `--report=YoastCS\Yoast\Reports\Threshold`.
        Note: depending on the OS the command is run on, the backslashes in the report name may need to be escaped (doubled).
* PHPCS: The `PSR12.ControlStructures.BooleanOperatorPlacement` sniff.
    Enforces that boolean operators in multi-line control structures are always placed at the start of a line.
* PHPCS: `Yoast.Commenting.CodeCoverageIgnoreDeprecated`: Support for attributes (PHP 8.0+) placed between a function or class declaration and the associated docblock.
* PHPCS: `Yoast.Commenting.TestHaveCoversTag`: Support for attributes (PHP 8.0+) placed between a function or class declaration and the associated docblock.
* PHPCS: `Yoast.NamingConventions.ObjectNameDepth`: Support for attributes (PHP 8.0+) placed between a function or class declaration and the associated docblock.
* PHPCS: `Yoast.NamingConventions.ObjectNameDepth`: Support for examining the word count in CamelCaps class names.
* PHPCS: `Yoast.NamingConventions.ValidHookName`: Verification that backslashes in namespace-like prefixes in double quoted strings are slash-escaped.
* An initial CONTRIBUTING file with guidelines on acceptance testing changes to the sniffs in this repository.

#### Changed
* PHPCS: The default value for the `minimum_supported_wp_version` property which is used by various WPCS sniffs has been updated to WP `5.7` (was `5.4`).
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.5.0` to `^3.6.0`.
* Composer: Supported version of [WordPressCS] has been changed from `^2.2.0` to `^2.3.0`.
* Composer: Updated the supported versions of dev dependencies.
* Readme: Minor documentation improvements.
* Continuous Integration: CI has been moved from Travis to GitHub Actions.
* Various housekeeping.

#### Fixed
* PHPCS: `Yoast.Commenting.CoversTag`: `@covers` tags refering to classes and functions which don't follow the WordPressCS naming conventions will now be regarded as valid.
* PHPCS: `Yoast.Commenting.TestsHaveCoversTag`: the sniff will now also report missing `@covers` tags for test methods without docblock.
* PHPCS: `Yoast.Commenting.TestsHaveCoversTag`: the determination whether a class or method is a test class or method has been made more flexible to allow for different test naming conventions.
* PHPCS: `Yoast.Commenting.TestsHaveCoversTag`: will no longer expect a `@covers` tag for abstract test methods.
* PHPCS: `Yoast.Files.FileComment`: fixed performance issue.
* PHPCS: `Yoast.Files.FileName`: will no longer throw an error when a class names is an exact match for one of the "removable" prefixes (as there would be nothing left to name the file as).
* PHPCS: `Yoast.NamingConventions.ObjectNameDepth`: the object name depth for underscore prefixed class names will now be calculated correctly.
* PHPCS: `Yoast.NamingConventions.ValidHookName`: will now recognize slash-escaped backslashes in namespace-like prefixes correctly when in a double quoted string.


### [2.1.0] - 2020-10-27

#### Added
* PHPCS: A new check to the `Yoast.Files.TestDoubles` sniff to verify that all double/mock classes have either `Double` or `Mock` in the class name.
* PHPCS: Metrics to the `Yoast.NamingConventions.NamespaceName` sniff to see the breakdown of the number of levels used in namespace names across a code base.
    To see the metrics, run PHPCS with the `--report=info` option.
* PHPCS: Metrics to the `Yoast.NamingConventions.ObjectNameDepth` sniff to see the breakdown of the number of words used in object names across a code base.
    To see the metrics, run PHPCS with the `--report=info` option.
* PHPCS: Metrics to the `Yoast.NamingConventions.ValidHookName` sniff to see the breakdown of the number of words used in hook names across a code base, as well as a break down of old-style versus new-style hook name usage.
    To see the metrics, run PHPCS with the `--report=info` option.

#### Changed
* PHPCS: The default value for the `minimum_supported_wp_version` property which is used by various WPCS sniffs has been updated to WP `5.4` (was `5.3`).
* Composer: Supported version of the [Composer PHPCS plugin] has been changed to allow for the newly released `0.7.0` version which adds support for Composer 2.0.
    Note: this requirement is flexible to prevent conflicts with included standards which may include the plugin as well.
* Travis: improved testing against the upcoming PHP 8.0.
* Various housekeeping.

#### Fixed
* PHPCS: The `Yoast.Files.FileName` sniff expects a `-functions` suffix for a function-only file. The sniff has been updated to also allow such a file to be called `functions.php` without further specification.
    _This widening is specifically intended for namespaced function-only files._
* PHPCS: The `Yoast.NamingConventions.NamespaceName` sniff has improved handling of the allowance for an extra namespace level for test double directories for non-conventional test directory set-ups (like YoastSEO).


### [2.0.2] - 2020-04-02

#### Changed
* PHPCS: The default value for the `minimum_supported_wp_version` property which is used by various WPCS sniffs has been updated to WP `5.3` (was `5.2`).


### [2.0.1] - 2020-02-06

#### Changed
* Composer: Supported version of the [Composer PHPCS plugin] has been changed from `^0.5.0` to `^0.5 || ^0.6`.
    Note: this requirement is flexible to prevent conflicts with included standards which may include the plugin as well.
* Various housekeeping.

#### Fixed
* PHPCS: `Yoast.NamingConventions.NamespaceName`: fixed a potential "undefined index" notice.


### [2.0.0] - 2019-12-17

#### Added
* PHPCS: New `Yoast.NamingConventions.ObjectNameDepth` sniff.
    - For objects _declared within a namespace_, this sniff verifies that an object name consist of maximum three words separated by underscores.
    - For objects which are part of a unit test suite, a `_Test`, `_Mock` or `_Double` suffix at the end of the object name will be disregarded for the purposes of the word count.
    - The sniff has two configurable properties `max_words` (error) and `recommended_max_words` (warning). The default for both is `3`.
* PHPCS: New `Yoast.NamingConventions.NamespaceName` sniff.
    This sniff verifies that:
    - Namespace names consist of a maximum of three levels (excluding the plugin specific prefix) and recommends for the name to be maximum two levels deep.
        For unit test files, `Tests\(Doubles\)` directly after the prefix will be ignored when determining the level depth.
    - The levels in the namespace name directly translate to the directory path to the file.
    - The sniff has four configurable properties:
        - `max_levels` (error) and `recommended_max_levels` (warning) which are by default set to `3` and `2` respectively.
        - `src_directory` to indicate the project root(s) for the _path-to-name_ translation when the project root is not the repo root directory.
        - `prefixes` to set the plugin specific prefix(es) to take into account.
* PHPCS: New `Yoast.NamingConventions.ValidHookName` sniff.
    This sniff extends and adds to the upstream `WordPress.NamingConventions.ValidHookName` sniff.
    The sniff will ignore non-prefixed hooks and hooks with a prefix unrelated to the plugin being examined, to prevent errors being thrown about hook names which are outside of our control.
    This sniff verifies that:
    - Hook names are in lowercase with words separated by underscores (same as WordPressCS).
    - Hook names are prefixed with the plugin specific prefix in namespace format, i.e. `Yoast\WP\PluginName`.
        Note: The prefix is exempt from the _lowercase with words separated by underscores_ rule.
        If the non-namespace type prefix for a plugin is used, the sniff will throw a `warning`.
    - The actual hook name (after the prefix) consist of maximum four words separated by underscores.
        - Note: _The hook_name part should be descriptive for the (dev-)user and does not need to follow the namespace or file path of the file they are in._
        - Also note: for dynamic hook names where the hook name length can not reliably be determined, the sniff will throw a `warning` at severity `3` suggesting the hook name be inspected manually.
            As the default `severity` for PHPCS is `5`, this `warning` at severity `3` will normally not be shown.
            It is there to allow for intermittently checking of the dynamic hook names. To trigger it, `--severity=3` should be passed on the command line.
    - The sniff has three configurable properties:
        - `maximum_depth` (error) and `soft_maximum_depth` (warning). The default for both is `4`.
        - `prefixes` to set the plugin specific prefix(es) to take into account.
* PHPCS: The `Generic.Arrays.DisallowLongArraySyntax` sniff.
    WPCS 2.2.0 demands long array syntax. In contrast to that, YoastCS demands short array syntax.
* PHPCS: The `Generic.ControlStructures.DisallowYodaConditions` sniff.
    In contrast to WPCS, YoastCS never demanded Yoda conditions. With the addition of this sniff, "normal" (non-Yoda) conditions will now be enforced.
* PHPCS: The `Generic.WhiteSpace.SpreadOperatorSpacingAfter` sniff.
    Enforces no space between the `...` spread operator and the variable/function call it applies to.
* PHPCS: The `PEAR.WhiteSpace.ObjectOperatorIndent` sniff.
    Enforce consistent indentation of chained method calls to one more or less than the previous call in the chain and always at least one in from the start of the chain.
* PHPCS: The `PSR12.Classes.ClosingBrace` sniff.
    This sniff disallows the outdated practice of `// end ...` comments for OO stuctures.
* PHPCS: The `PSR12.Files.ImportStatement` sniff.
    Import `use` statements must always be fully qualified, so a leading backslash is redundant (and discouraged by PHP itself).
    This sniff enforces that no leading backslash is used for import `use` statements.
* PHPCS: The `PSR12.Files.OpenTag` sniff.
    Enforces that a PHP open tag is on a line by itself in PHP-only files.
* PHPCS: A `CustomPrefixesTrait` to handle checking names against a list of custom prefixes.
* Composer: `lint` script which uses the [PHP Parallel Lint] package for faster and more readable linting results.

#### Changed
* :warning: PHPCS: `Yoast.Files.FileName` sniff: the public `$prefixes` property, which can be used to indicate which _prefixes_ should be stripped of a class name when translating it to a file name, has been renamed to `$oo_prefixes`.
    Custom repo specific rulesets using the property should be updates to reflect this change.
* :warning: PHPCS: `Yoast.Files.FileName` sniff: the public `$exclude` property, which can be used to indicate which files to exclude from the file name versus object name check, has been renamed to `$excluded_files_strict_check`.
    Custom repo specific rulesets using the property should be updates to reflect this change.
* PHPCS: The default setting for the minimum supported PHP version for repos using YoastCS is now PHP 5.6 (was 5.2).
* PHPCS: The default value for the `minimum_supported_wp_version` property which is used by various WPCS sniffs has been updated to WP `5.2` (was `4.9`).
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.4.2` to `^3.5.0`.
    Note: this makes the option `--filter=gitstaged` available which can be used in git `pre-commit` hooks to only check staged files.
* Composer: Supported version of [WordPressCS] has been changed from `^2.1.1` to `^2.2.0`.
* Composer: Supported version of [PHPCompatibilityWP] has been changed from `^2.0.0` to `^2.1.0`.
* Travis: the build check is now run in stages.
* Travis: Tests against PHP 7.4 are no longer allowed to fail.
* Various housekeeping & code compliance with YoastCS 2.0.0.


### [1.3.0] - 2019-07-31

#### Added
* PHPCS: New `Yoast.Commenting.CoversTag` sniff.
    This sniff verifies that:
    - the contents of a `@covers` annotation is valid based on what's supported by PHPUnit;
    - there are no duplicate `@covers` or `@coversNothing` tags in a docblock;
    - a docblock doesn't contain both a `@covers` tag as well as a `@coversNothing` tag;
    Includes a fixer for common errors.
* PHPCS: New `Yoast.Commenting.TestsHaveCoversTag` sniff.
    This sniff verifies that all unit test functions have at least one `@covers` tag - or a `@coversNothing` tag - in the function docblock or in the class docblock.
* PHPCS: New `Yoast.Yoast.AlternativeFunctions` sniff.
    This sniff allows for discouraging/forbidding the use of PHP/WP native functions in favor of using Yoast native functions.
    In this initial version, the sniff checks for the use of the `json_encode()` and `wp_json_encode()` functions and suggests using `WPSEO_Utils::format_json_encode()` instead.
    Note: this sniff contains an auto-fixer. If for any of the repos, the auto-fixer should not be used, the auto-fixer can be disabled from within the repo specific ruleset using `<rule ref="..." phpcs-only="true"/>`.
* PHPCS: The `Squiz.WhiteSpace.MemberVarSpacing` sniff.
    This sniff verifies and auto-fixes the number of blank lines between property declarations within OO-structures.
* PHPCS: A default value for the `minimum_supported_wp_version` property which is used by various WPCS sniffs. The current default is WP `4.9`.
   Previously this value would have to be set via a `config` directive in custom repo specific rulesets.
   For those rulesets which use the Yoast default, this `config` directive can now be removed.
   For more details, see [#131](https://github.com/Yoast/yoastcs/pull/131).
* PHPCS: All YoastCS native sniffs are now accompanied by documentation which can be viewed from the command-line using `phpcs --generator=Text --standard=Yoast`.
* Repo/QA: Various templates for typical pull requests to this repo.
* Composer: `fix-cs` script.
* Travis: Testing of the code against PHP 7.4 (unstable).

#### Changed
* PHPCS: Files in the following directories will now be excluded from all scans by default:
    - `/.git/`
    - `/.wordpress-svn/`
    - `/node-modules/`
    - `/vendor/`
    - `/vendor_prefixed/`
    Custom repo specific rulesets which contain excludes to this effect, can now remove them safely.
* PHPCS: The message type for issues reported by the `Generic.Formatting.MultipleStatementAlignment` and `WordPress.Arrays.MultipleStatementAlignment` sniffs, has been upgraded from `warning` to `error`.
* PHPCS: The WPCS native check for the `json_encode()` function in the `WordPress.WP.AlternativeFunctions` has been disabled in favor of the new YoastCS native `Yoast.Yoast.AlternativeFunctions` sniff.
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.4.0` to `^3.4.2`.
* Composer: Supported version of [WordPressCS] has been changed from `^2.0.0` to `^2.1.1`.
* Travis: As there is now a sniff which extends a WPCS sniff, the unit tests will now run against various combinations of PHPCS and WPCS combined.
* Minor housekeeping.

#### Removed
* PHPMD is no longer part of the YoastCS repo.
    PHPMD was not used as a stand-alone tool by any of the repos, only in combination with CodeClimate.
* Travis: Testing of the repo against PHP `nightly` (PHP 8, unstable) as no viable PHPUnit version is currently available.

#### Fixed
* PHPCS: The `Yoast.Files.FileName` sniff will now always suggest removing the longest prefix of the prefixes passed in the configuration.

### [1.2.2] - 2019-01-21

#### Changed
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.3.2` to `^3.4.0`.
* Composer: Supported version of [WordPressCS] has been changed from `^1.2.0` to `^2.0.0`.
* PHPCS: The PHPCompatibility ruleset will now explicitly only be applied to PHP files.

### [1.2.1] - 2018-12-28

#### Fixed
* PHPCS: Undefined variable in the `Yoast.Namespaces.NamespaceDeclaration` sniff.

### [1.2.0] - 2018-12-21

#### Added
* PHPCS: New `Yoast.Commenting.FileComment` sniff.
    This sniff is a wrapper around the `FileComment` sniff used in WordPressCS and manages the slightly different requirements for file comments set for the Yoast organisation, in particular:
    - If a file is namespaced, no file comment is needed (and having one is discouraged).
* PHPCS: New `Yoast.Namespaces.NamespaceDeclaration` sniff.
    This sniff forbids the use of:
    - Namespace declarations without a namespace name, i.e. `namespace;` which in effect means "global namespace".
    - Scoped namespace declarations.
    - Multiple namespace declarations in one file.

### [1.1.0] - 2018-12-18

#### Added
* PHPCS: New `Yoast.Commenting.CodeCoverageIgnoreDeprecated` sniff.
    This sniff verifies that functions which have a `@deprecated` tag in the function docblock, also have a `@codeCoverageIgnore` tag in the same docblock.
* PHPCS: Added XSD schema tags to the ruleset.
* Composer: requirement of the [Composer PHPCS plugin] at version `^0.5.0`.
    This means that - in most cases - projects which `require(-dev)` YoastCS, will no longer need to have the plugin in their own `composer.json` and will still get the benefit of it.
* Travis: Validation of the ruleset against the PHPCS XSD schema.
* Travis: Testing of the code against PHP 7.3.

#### Changed
* PHPCS: The `Yoast.Files.TestDoubles` sniff now allows setting multiple valid paths for test doubles to be placed in.
    To this end, the public `doubles_path` property has been changed from a `string` to an `array`.
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.3.1` to `^3.3.2`.
* Composer: Supported version of [WordPressCS] has been changed from `^1.0.0` to `^1.2.0`.
* Composer: Supported version of [PHPCompatibilityWP] has been changed from `^1.0.0` to `^2.0.0`, which uses [PHPCompatibility] `^9.0.0` under the hood.
* Composer: The `config-set` script for use with this repo has been renamed to `config-yoastcs` to be in line with the same script in other repos.
* Minor housekeeping: updated `.gitignore`.

#### Removed
* PHPCS: Minor housekeeping: removed some unused code.
* Composer: `suggest` section. The [Composer PHPCS plugin] is now included in the `require` section.

#### Fixed
* PHPCS: Various fixes to the `Yoast.Files.TestDoubles` sniff.
    - If the `basepath` contained a trailing slash, the sniff could give incorrect results.
    - Prevent the sniff from recognizing a path like `/tests/doublesniff` as correct when `/test/doubles` is in the allowed list.
    - The `OneObjectPerFile` check will now check both code _above_ the detected mock/double class as well as code _below_ it.

### [1.0.0] - 2018-08-24

#### Added
* PHPCS: New `Yoast.Files.TestDoubles` sniff.
    This sniff verifies that test `double`/`mock` classes are in their own file in a `doubles` test sub-directory.
* PHPCS: New `Yoast.WhiteSpace.FunctionSpacing` sniff.
    This sniff is based on the PHPCS native `Squiz.WhiteSpace.FunctionSpacing` sniff and verifies and auto-fixes the amount of blank lines between methods within OO-structures.
* PHPCS: The `Generic.PHP.LowerCaseType` sniff, as introduced in PHP_CodeSniffer 3.3.0, to the YoastCS ruleset.
* PHPCS: The `PSR2.Methods.FunctionClosingBrace` sniff to the YoastCS ruleset.
* PHPCS: The `PSR12.Keywords.ShortFormTypeKeywords` sniff, as introduced in PHP_CodeSniffer 3.3.0, to the YoastCS ruleset.
* Composer: `roave/security-advisories` dependency to prevent dependencies with known security issues from being installed.
* Composer: An explanation about the [Composer PHPCS plugin] dependency suggestion this package makes.
* Composer: `--dev` requirement of the [PHPCompatibility] library at version `^8.2.0`.

#### Changed
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^3.2.0` to `^3.3.1`.
* Composer: Supported version of [WordPressCS] has been changed from `~0.14.0` to `^1.0.0`.
* Composer/PHPCS: Switched from using the external [PHPCompatibility] standard to using [PHPCompatibilityWP] at version `^1.0.0`.
* Composer: The command to run `check-cs` over the code in this repo.
    PHP_CodeSniffer 3.3.0 allows overruling config directives from the command-line. This also simplies the ruleset used for the YoastCS codebase.
* PHPCS: The format for declaring array properties in the ruleset has been updated to the new format available in PHP_CodeSniffer 3.3.0.
* Travis: The installation of dependencies is now done via Composer instead of via cloning repos.
* Travis: CS warnings are no longer allowed in this codebase. The codebase is currently 100% clean, let's keep it that way.

#### Removed
* Composer: The `composer.lock` file.
    This file is not necessary for library packages and makes the testing of YoastCS sniffs more involved.
* PHPCS: Custom excludes for the `PHPCompatibility` ruleset.
    These are no longer necessary after the switch to `PHPCompatibilityWP`.
* PHPCS: Some stray debug code.
* PHPCS: `// End ...` comments from the codebase.
* Travis: Work-around for the PHPUnit version in the PHP 7.2 and nightly images as PHP_CodeSniffer supports PHPUnit 6.x since version 3.3.0.

#### Fixed
* Travis: Builds on PHP 7.2 and nightly were failing because of changes in the Travis images (higher PHPUnit version).

### [0.5] - 2018-01-25

#### Added
* PHPCS: New `Yoast.Files.FileName` sniff and exclude the whole `WordPress.Files.FileName` sniff.
    The `Yoast.Files.FileName` sniff verifies that file names comply with the Yoast specific file name rules.
* PHPCS: The external [PHPCompatibility] standard to the YoastCS ruleset at version `^8.1.0`.
    Checking against this standard was previously disabled as it was incompatible with Composer. This has since been fixed.
* PHPCS: A WP specific set of excludes for the `PHPCompatibility` standard to prevent false positives for native PHP functionality which is back-filled by WordPress.
* PHPCS: A custom configuration for the new `WordPress.Arrays.MultipleStatementAlignment` sniff as introduced in WordPressCS 0.14.0.
* PHPCS: An exclusion for the `WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition` error code.
* PHPCS: An exclusion for the `WordPress.PHP.StrictInArray.FoundNonStrictFalse` error code.
* Composer: Repository `type` indicator for compatibility with Composer plugins which handle the setting of the PHP_CodeSniffer `installed_paths` directive.
* Composer: Suggest requiring the [Composer PHPCS plugin], i.e. `dealerdirect/phpcodesniffer-composer-installer`, for handling the PHP_CodeSniffer `installed_paths` directive at version `^0.4.3`.
* Travis: Validation and CS check of the XML files.
* Travis: Testing of the repo against PHP 7.2.
* Travis: Checking of the code style of the YoastCS native PHP files.
* Travis: Validation of the `composer.json` file.
* A `.gitattributes` file to keep the code distributed via GH archives and Packagist clean of development related files.
* A custom PHPCS ruleset, `.phpcs.xml.dist`, based on YoastCS to check the code style of the code in the YoastCS repository itself.
* A `phpunit.xml.dist` file to document the PHPUnit configuration used for testing the YoastCS native sniffs.

#### Changed
* The minimum supported PHP version for YoastCS is now PHP 5.4 (was 5.2).
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `2.8.1` to `^3.2.0`.
* Composer: Supported version of [WordPressCS] has been changed from `~0.10.0` to `~0.14.0`.
* PHPCS: The native YoastCS sniffs are now compatible with PHP_CodeSniffer 3.x.
* PHPCS: Improvements to the native `Yoast.ControlStructures.IfElseDeclaration` sniff:
    - The sniff now has improved detection of issues when non-custom code style is used.
    - The sniff has been made more efficient and will exit earlier when no issues are or can be detected.
    - The error message has been made more descriptive.
    - The sniff will no longer hide one error behind another.
    - The sniff will no longer throw false positives when the new PHP_CodeSniffer 3.2.0 inline annotations are encountered.
* Improved inline documentation in the PHPCS ruleset.
* Travis: Minor tweaks to make the builds more efficient.

#### Removed
* Composer: Superfluous `conflict` directive.
    This has been superseded by a higher minimum PHP_CodeSniffer requirement.
* PHPCS: Support for PHP_CodeSniffer 2.x for the YoastCS native sniffs.
* PHPCS: The exclusion of the `Generic.Files.LineEndings.InvalidEOLChar` error code.
* PHPCS: The inclusion of the `Generic.Strings.UnnecessaryStringConcat` sniff, including custom configuration.
    This sniff is now included in WordPressCS since version 0.11.0.
* PHPCS: The exclusion of the `PEAR.Functions.FunctionCallSignature.Indent` error code as code should comply with this.
* PHPCS: The inclusion of the `Squiz.ControlStructures` category and the related detail configuration.
    This is now largely covered by WordPressCS 0.14.0.
* PHPCS: The exclusion of the whole `WordPress.Variables.GlobalVariables` sniff.
    This sniff was previously excluded because of a bug in WordPressCS. This bug has been fixed in WordPressCS 0.11.0.
* PHPCS: The exclusion of the `WordPress.VIP.DirectDatabaseQuery`, `WordPress.VIP.FileSystemWritesDisallow`, `WordPress.XSS.EscapeOutput`, `WordPress.VIP.ValidatedSanitizedInput` sniffs and the `Generic.Commenting.DocComment.MissingShort` error code.
    These kind of excludes should be handled in the plugin specific rulesets, not across the board in the YoastCS ruleset.
* PHPCS: The group exclusion configurations for the `WordPress.VIP.RestrictedFunctions`, `WordPress.VIP.RestrictedVariables` and the `WordPress.VIP.PostsPerPage` sniffs.
    This kind of configuration should be handled in the plugin specific rulesets, not across the board in the YoastCS ruleset.

#### Fixed
* Code style consistency for the `xml` files in this repository.
* Code style consistency for the `php` files in this repository.
* PHPCS: Exclusion patterns for the empty `index.php` files.


### [0.4.3] - 2017-08-02

#### Added
* PHPCS: The `Generic.Strings.UnnecessaryStringConcat` sniff to the YoastCS ruleset.
* PHPCS: An exclusion for the `Generic.PHP.Syntax` sniff.
    The Yoast plugins all run `php lint` against a variety of PHP versions, which is the recommended way to check for PHP syntax errors.
* PHPCS: Exclusions for the following additional function groups for the `WordPress.VIP.RestrictedFunctions` sniff: `error_log`, `runtime_configuration`, `prevent_path_disclosure`, `url_to_postid`.

#### Changed
* Travis: Limit the testing of the sniff unit tests to PHP_CodeSniffer 2.x as YoastCS does not yet support PHP_CodeSniffer 3.x.

#### Fixed
* Travis: Builds for PHP 5.2 and 5.3 were failing because of changes in the Travis images.

### [0.4.2] - 2017-03-22

#### Changed
* Composer: Included version of [PHP_CodeSniffer] has been changed from `2.7.0` to `2.8.1`.
    The minimum supported version remains at `2.8.1` as updated in YoastCS 0.4.1.
* Composer: Included version of [PHP Mess Detector] has been updated from `2.4.3` to `2.6.0`.
    The minimum supported version remains at `2.2.3`.
* PHPCS: YoastCS is now based on the full `WordPress` ruleset, rather than the `WordPress-VIP` ruleset. See: [#16](https://github.com/Yoast/yoastcs/issues/16).

#### Removed
* PHPCS: The exclusion of the whole `WordPress.NamingConventions.ValidVariableName` sniff.
    This sniff was previously excluded because of a bug in WordPressCS. This bug has been fixed in WordPressCS 0.10.0.


### [0.4.1] - 2017-03-21

#### Added
* PHPCS: Unit tests for the `Yoast.ControlStructures.IfElseDeclaration` sniff.
* Travis build testing.

#### Changed
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `2.7.0` to `~2.8.1`.
* PHPCS: Exclude function groups rather than individual error codes for WordPressCS sniffs which allow for this.

#### Removed
* PHPCS: The exclusion for the `Squiz.Commenting.FunctionComment.ScalarTypeHintMissing` error code.
    This is already taken care of upstream in WordPressCS 0.10.0.
* PHPCS: Explicit inclusion of the YoastCS native `Yoast.ControlStructures.IfElseDeclaration` sniff from the YoastCS ruleset.
    All `Yoast` sniffs are automatically included, so the explicit inclusion was redundant.


### [0.4] - 2016-09-06

#### Changed
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `2.5.1` to `~2.7.0`.
* Composer: Supported version of [WordPressCS] has been changed from `0.9` to `~0.10.0`.
* Composer: Included version of [PHP Mess Detector] has been updated from `2.4.2` to `2.4.3`.
    The minimum supported version remains at `2.2.3`.

### [0.3] - 2016-05-03

#### Added
* PHPCS: An exclusion for the `WordPress.VIP.RestrictedFunctions.get_pages` error code to the YoastCS ruleset.


### [0.2] - 2016-04-01

#### Added
* PHPCS: An exclusion for the `PEAR.Functions.FunctionCallSignature.Indent` error code to the YoastCS ruleset.
* PHPCS: An exclusion for the `Squiz.Commenting.FunctionComment.ScalarTypeHintMissing` error code.
* PHPCS: An exclusion for the whole `WordPress.NamingConventions.ValidVariableName` sniff.
* PHPCS: An exclusion for the `WordPress.VIP.RestrictedFunctions.count_user_posts` error code.
* PHPMD: `wp` to the list of common exceptions to the `ShortVariable` name rule.

#### Changed
* Composer: Supported version of [PHP_CodeSniffer] has been changed from `^2.2.0` to `2.5.1`.
* Composer: Supported version of [WordPressCS] has been changed from `0.6` to `0.9`.
* Composer: Included version of [PHP Mess Detector] has been updated from `2.2.3` to `2.4.2`.
    The minimum supported version remains at `2.2.3`.

#### Removed
* PHPCS: The exclusion of the whole `WordPress.NamingConventions.ValidFunctionName` sniff from the YoastCS ruleset.


### 0.1 - 2016-01-08

Initial public release as a stand-alone package.


[PHP_CodeSniffer]:               https://github.com/PHPCSStandards/PHP_CodeSniffer/releases
[Composer PHPCS plugin]:         https://github.com/PHPCSStandards/composer-installer/releases
[PHPCompatibilityWP]:            https://github.com/PHPCompatibility/PHPCompatibilityWP#changelog
[PHPCompatibility]:              https://github.com/PHPCompatibility/PHPCompatibility/blob/master/CHANGELOG.md
[PHPCSUtils]:                    https://github.com/PHPCSStandards/PHPCSUtils/releases
[PHPCSExtra]:                    https://github.com/PHPCSStandards/PHPCSExtra/releases
[SlevomatCodingStandard]:        https://github.com/slevomat/coding-standard/releases
[VariableAnalysis]:              https://github.com/sirbrillig/phpcs-variable-analysis/releases
[WordPressCS]:                   https://github.com/WordPress/WordPress-Coding-Standards/blob/develop/CHANGELOG.md
[WordPressVIP Coding Standards]: https://github.com/Automattic/VIP-Coding-Standards/releases
[PHP Mess Detector]:             https://github.com/phpmd/phpmd/blob/master/CHANGELOG
[PHP Parallel Lint]:             https://github.com/php-parallel-lint/PHP-Parallel-Lint/releases
[PHP Console Highlighter]:       https://github.com/php-parallel-lint/PHP-Console-Highlighter/releases

[3.4.0]: https://github.com/Yoast/yoastcs/compare/3.3.0...3.4.0
[3.3.0]: https://github.com/Yoast/yoastcs/compare/3.2.0...3.3.0
[3.2.0]: https://github.com/Yoast/yoastcs/compare/3.1.0...3.2.0
[3.1.0]: https://github.com/Yoast/yoastcs/compare/3.0.0...3.1.0
[3.0.0]: https://github.com/Yoast/yoastcs/compare/2.3.1...3.0.0
[2.3.1]: https://github.com/Yoast/yoastcs/compare/2.3.0...2.3.1
[2.3.0]: https://github.com/Yoast/yoastcs/compare/2.2.1...2.3.0
[2.2.1]: https://github.com/Yoast/yoastcs/compare/2.2.0...2.2.1
[2.2.0]: https://github.com/Yoast/yoastcs/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/Yoast/yoastcs/compare/2.0.2...2.1.0
[2.0.2]: https://github.com/Yoast/yoastcs/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/Yoast/yoastcs/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/Yoast/yoastcs/compare/1.3.0...2.0.0
[1.3.0]: https://github.com/Yoast/yoastcs/compare/1.2.2...1.3.0
[1.2.2]: https://github.com/Yoast/yoastcs/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/Yoast/yoastcs/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/Yoast/yoastcs/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/Yoast/yoastcs/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/Yoast/yoastcs/compare/0.5.0...1.0.0
[0.5]: https://github.com/Yoast/yoastcs/compare/0.4.3...0.5
[0.4.3]: https://github.com/Yoast/yoastcs/compare/0.4.2...0.4.3
[0.4.2]: https://github.com/Yoast/yoastcs/compare/0.4.1...0.4.2
[0.4.1]: https://github.com/Yoast/yoastcs/compare/0.4...0.4.1
[0.4]: https://github.com/Yoast/yoastcs/compare/0.3...0.4
[0.3]: https://github.com/Yoast/yoastcs/compare/0.2...0.3
[0.2]: https://github.com/Yoast/yoastcs/compare/0.1...0.2

[@GaryJones]: https://github.com/GaryJones
