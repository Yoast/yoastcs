# Change Log for YoastCS

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](https://semver.org/) and [Keep a CHANGELOG](https://keepachangelog.com/).


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
* Composer: An explanation about the [DealerDirect Composer PHPCS plugin] dependency suggestion this package makes.
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
* Composer: Suggest requiring the [DealerDirect Composer PHPCS plugin], i.e. `dealerdirect/phpcodesniffer-composer-installer`, for handling the PHP_CodeSniffer `installed_paths` directive at version `^0.4.3`.
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


[PHP_CodeSniffer]: https://github.com/squizlabs/PHP_CodeSniffer/releases
[WordPressCS]: https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/blob/develop/CHANGELOG.md
[PHPCompatibilityWP]: https://github.com/PHPCompatibility/PHPCompatibilityWP#changelog
[PHPCompatibility]: https://github.com/PHPCompatibility/PHPCompatibility/blob/master/CHANGELOG.md
[PHP Mess Detector]: https://github.com/phpmd/phpmd/blob/master/CHANGELOG
[DealerDirect Composer PHPCS plugin]: https://github.com/Dealerdirect/phpcodesniffer-composer-installer/releases

[1.0.0]: https://github.com/Yoast/yoastcs/compare/0.5.0...1.0.0
[0.5]: https://github.com/Yoast/yoastcs/compare/0.4.3...0.5
[0.4.3]: https://github.com/Yoast/yoastcs/compare/0.4.2...0.4.3
[0.4.2]: https://github.com/Yoast/yoastcs/compare/0.4.1...0.4.2
[0.4.1]: https://github.com/Yoast/yoastcs/compare/0.4...0.4.1
[0.4]: https://github.com/Yoast/yoastcs/compare/0.3...0.4
[0.3]: https://github.com/Yoast/yoastcs/compare/0.2...0.3
[0.2]: https://github.com/Yoast/yoastcs/compare/0.1...0.2
