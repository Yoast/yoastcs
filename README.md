# Yoast Coding Standards

Yoast Coding Standards (YoastCS) is a project with rulesets for code style and quality tools to be used in Yoast projects.

## Installation

### Standalone

Standards are provided as a [Composer](https://getcomposer.org/) package and can be installed with:

```bash
composer create-project yoast/yoastcs:dev-master
```

Composer will automatically install dependencies, register standards paths, and set default PHP Code Sniffer standard to `Yoast`.

### As dependency

To include standards as part of a project require them as development dependencies:

```bash
composer require --dev yoast/yoastcs:^1.0
```

Composer will automatically install dependencies and register the YoastCS and other external standards with PHP_CodeSniffer.

## PHP Code Sniffer

Set of [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) rules.

Severity levels:

 - error level issues are considered mandatory to fix in Yoast projects and enforced in continuous integration
 - warning level issues are considered recommended to fix

### The YoastCS Standard

The `Yoast` standard for PHP_CodeSniffer is comprised of the following:
* The `WordPress` ruleset from the [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) implementing the official [WordPress PHP Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/php/), with some [select exclusions](https://github.com/Yoast/yoastcs/blob/develop/Yoast/ruleset.xml#L29-L75).
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
"vendor/bin/phpcs" --standard=Yoast --generator=text
```

### Running the sniffs

#### Command line

```bash
"vendor/bin/phpcs" --extensions=php /path/to/folder/
```

For more command-line options, please have a read through the [PHP_CodeSniffer documentation](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Usage).

#### Yoast plugin repositories

All Yoast plugin repositories contain a `[.]phpcs.xml.dist` file contain the repository specific configuration.

From the root of these repositories, you can run PHPCS by using:
```bash
composer check-cs
```

#### PhpStorm

Refer to [Using PHP Code Sniffer Tool](https://www.jetbrains.com/phpstorm/help/using-php-code-sniffer-tool.html) in the PhpStorm documentation.

After installation, the `Yoast` standard will be available as a choice in PHP Code Sniffer Validation inspection.

## Changelog

The changelog for this package can be found in the [CHANGELOG.md](https://github.com/Yoast/yoastcs/blob/develop/CHANGELOG.md) file.
