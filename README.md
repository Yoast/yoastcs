# Yoast Coding Standards

Yoast Coding Standards (YoastCS) is a project with rulesets for code style and quality tools to be used in Yoast projects.

## Installation

### Standalone

Standards are provided as a [Composer](https://getcomposer.org/) package and can be installed with:

```bash
composer create-project yoast/yoastcs:"dev-main"
```

Composer will automatically install dependencies, register standards paths, and set default PHP Code Sniffer standard to `Yoast`.

### As dependency

To include standards as part of a project require them as development dependencies:

```bash
composer config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
composer require --dev yoast/yoastcs:"^2.0"
```

Composer will automatically install dependencies and register the YoastCS and other external standards with PHP_CodeSniffer.

## Tools provided via YoastCS

* PHP Parallel Lint
* PHP_CodeSniffer and select standards for PHP_CodeSniffer, including a number of Yoast native sniffs.


## PHP Parallel Lint

[PHP Parallel Lint](https://github.com/php-parallel-lint/PHP-Parallel-Lint/) is a tool to lint PHP files against parse errors.

PHP Parallel Lint does not use a configuration file, so [command-line options](https://github.com/php-parallel-lint/PHP-Parallel-Lint/#command-line-options) need to be passed to configure what files to scan.

It is best practice within the Yoast projects, to add a script to the `composer.json` file which encapsules the command with the appropriate command-line options to ensure that running the tool will yield the same results each time.

Typically, (a variation on) the following snippet would be added to the `composer.json` file for a project:
```json
    "scripts" : {
        "lint": [
            "@php ./vendor/php-parallel-lint/php-parallel-lint/parallel-lint . -e php --show-deprecated --exclude vendor --exclude .git"
        ]
    }
```


## PHP Code Sniffer

Set of [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) rules.

Severity levels:

 - error level issues are considered mandatory to fix in Yoast projects and enforced in continuous integration
 - warning level issues are considered recommended to fix

### The YoastCS Standard

The `Yoast` standard for PHP_CodeSniffer is comprised of the following:
* The `WordPress` ruleset from the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) implementing the official [WordPress PHP Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/php/), with some [select exclusions](https://github.com/Yoast/yoastcs/blob/develop/Yoast/ruleset.xml#L29-L75).
* The [`PHPCompatibilityWP`](https://github.com/PHPCompatibility/PHPCompatibilityWP) ruleset which checks code for PHP cross-version compatibility while preventing false positives for functionality polyfilled within WordPress.
* Select additional sniffs taken from [`PHP_CodeSniffer`](https://github.com/squizlabs/PHP_CodeSniffer).
* A number of custom Yoast specific sniffs.

Files within version management and dependency related directories, such as the Composer `vendor` directory, are excluded from the scans by default.

#### Sniffs

To obtain a list of all sniffs used within YoastCS:
```bash
"vendor/bin/phpcs" -e --standard=Yoast
```

#### Sniff Documentation

Not all sniffs have documentation available about what they sniff for, but for those which do, this documentation can be viewed from the command-line:
```bash
"vendor/bin/phpcs" --standard=Yoast --generator=Text
```

### Running the sniffs

#### Command line

```bash
"vendor/bin/phpcs" --extensions=php /path/to/folder/
```

For more command-line options, please have a read through the [PHP_CodeSniffer documentation](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Usage).

#### Yoast plugin repositories

All Yoast plugin repositories contain a `[.]phpcs.xml.dist` file which contains the repository specific configuration.

From the root of these repositories, you can run PHPCS by using:
```bash
composer check-cs
```

#### PhpStorm

Refer to [Using PHP Code Sniffer Tool](https://www.jetbrains.com/phpstorm/help/using-php-code-sniffer-tool.html) in the PhpStorm documentation.

After installation, the `Yoast` standard will be available as a choice in PHP Code Sniffer Validation inspection.

### The YoastCS "Threshold" report

The YoastCS package includes a custom `YoastCS\Yoast\Reports\Threshold` report for PHP_CodeSniffer to compare the current PHPCS run results with predefined "threshold" settings.

The report will look in the runtime environment for the following two environment variables and will take the values of those as the thresholds to compare the PHPCS run results against:
* `YOASTCS_THRESHOLD_ERRORS`
* `YOASTCS_THRESHOLD_WARNINGS`

If the environment variables are not set, they will default to 0 for both, i.e. no errors or warnings allowed.

The report will not print any details about the issues found, it just shows a summary based on the thresholds:
```
PHP CODE SNIFFER THRESHOLD COMPARISON
------------------------------------------------------------------------------------------------------------------------
Coding standards ERRORS: 148/130.
Coding standards WARNINGS: 539/539.

Please fix any errors introduced in your code and run PHPCS again to verify.
Please fix any warnings introduced in your code and run PHPCS again to verify.
```

After the report has run, a global `YOASTCS_ABOVE_THRESHOLD` constant (boolean) will be available which can be used in calling scripts.

To use this report, run PHPCS with the following command-line argument: `--report=YoastCS\Yoast\Reports\Threshold`.
_Note: depending on the OS the command is run on, the backslashes in the report name may need to be escaped (doubled)._


## Changelog

The changelog for this package can be found in the [CHANGELOG.md](https://github.com/Yoast/yoastcs/blob/develop/CHANGELOG.md) file.
