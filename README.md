# Yoast Coding Standards

Yoast Coding Standards (YoastCS) is a project with rulesets for code style and quality tools to be used in Yoast projects.

## Installation

### Standalone

Standards are provided as [Composer](https://getcomposer.org/) package and can be installed with:

```bash
composer create-project yoast/yoastcs:dev-master
```

Composer will automatically install dependencies, register standards paths, and set default PHP Code Sniffer standard to `Yoast`.

### As dependency

To include standards as part of a project require them as development dependencies:

```bash
composer require yoast/yoastcs:dev-master --dev
```

Note that Composer won't run configuration scripts in this scenario and the root project needs to take care of it.

## PHP Code Sniffer

Set of [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) rules.

Based on [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) project, implementing official [WordPress PHP Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/php/).

Severity levels:

 - error level issues are considered mandatory to fix in Yoast projects and enforced in continuous integration
 - warning level issues are considered recommended to fix

### Command line

```bash
"vendor/bin/phpcs" --extensions=php /path/to/folder/
```

### PhpStorm

Refer to [Using PHP Code Sniffer Tool](https://www.jetbrains.com/phpstorm/help/using-php-code-sniffer-tool.html) in PhpStorm documentation.

After installation `Yoast` standard will be available as a choice in PHP Code Sniffer Validation inspection.

## PHP Mess Detector

Set of [PHP Mess Detector](http://phpmd.org/) rules.

Original ruleset, produced for Yoast projects, taking under consideration typical WordPress practices and current state of code base.

All issues are considered informational for code development and maintenance.

### Command line

```bash
"vendor/bin/phpmd" /path/to/folder/ text phpmd.xml
```

### PhpStorm

Refer to [Using PHP Mess Detector](https://www.jetbrains.com/phpstorm/help/using-php-mess-detector.html) in PhpStorm documentation.

After installation add `phpmd.xml` file from project as custom ruleset in PHP Mess Detector Validation inspection settings.


## Changelog

The changelog for this package can be found in the [CHANGELOG.md](https://github.com/Yoast/yoastcs/blob/develop/CHANGELOG.md) file.
