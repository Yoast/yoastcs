# Yoast Coding Standards

Yoast coding standards are set of [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) rules to be used in Yoast projects.

They are based on [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) project, implementing official [WordPress PHP Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/php/).

## Installation

### Standalone

Standards are provided as [Composer](https://getcomposer.org/) package and can be installed with:

```bash
composer create-project yoast/yoastcs:dev-master
```

Composer will automatically install dependencies, register standards paths, and set default standard to `Yoast`.

### As dependency

To include standards as part of project require them as development dependencies:

```bash
composer require yoast/yoastcs:dev-master --dev
```

Note that Composer won't run configuration scripts in this scenario and root project needs to take care of it.

## Usage

### Command line

```bash
"vendor/bin/phpcs" --extensions=php /path/to/folder/
```

### PhpStorm

Refer to [Using PHP Code Sniffer Tool](https://www.jetbrains.com/phpstorm/help/using-php-code-sniffer-tool.html) in PhpStorm documentation.

After installation `Yoast` standard will be available as a choice in PHP Code Sniffer Validation inspection.