# Contributing to YoastCS


## Acceptance testing

There are various ways to acceptance test a sniff change:
1. Based on the tests included with the change.
2. Based on a code sample with "good" and "bad" code.
3. Based on an existing plugin code base.

Note: the above list is ordered by difficulty. Testing via method 1 will generally be easiest, method 3 most complicated.

In the fold-outs below are step by step instructions on how to acceptance test via each of these methods.

---
<details>
  <summary>Step by step: acceptance test based on the integration tests included with the change</summary>

1. Check out a local copy of this repo using your favourite git tool.
2. Check out the branch containing the change.
3. Run `composer install`.
4. Revert the changes to the sniff file (do not commit!), but leave the changes to the test file(s) in place.
5. Run the tests using `composer test`. The tests should fail in the expected places (see the commit message of the change for what to expect and the line numbers to expect errors and warnings on in the `*Test.php` file for the sniff).
6. Reset the sniff file to its committed state.
7. Run the tests again using `composer test`. The tests should now pass.

</details>

<details>
  <summary>Step by step: acceptance test based on a code sample with "good" and "bad" code</summary>

1. Check out a local copy of this repo using your favourite git tool.
2. Check out the `develop` branch.
3. Run `composer install`.
4. Create a simple PHP file with some code which should be flagged and some code which shouldn't be flagged.
5. Save this file in a `temp` subdirectory in your local YoastCS copy and make sure to add this `temp` directory to your `.git/info/exclude` file.
    Do not commmit this file!
6. Run `vendor/bin/phpcs -ps ./temp/yourfilename.php --standard=Yoast --report=full,source`.
    This should fail in the expected places (see the commit message of the change for what to expect), i.e. things not getting flagged which should get flagged or things getting flagged, which shouldn't get flagged.
    Pro-tip: if the test is just for a single sniff, you can limit the output to just that sniff by adding `--sniffs=Yoast.Category.SniffName` (replace `Category` and `SniffName` with the applicable names).
7. Check out the branch containing the change.
8. Run the command from [6] again. The sniff should now flag things correctly.

If the sniff change includes changes to/adding of auto-fixers, the fixing should also be tested.

9. Check out the `develop` branch again.
10. Run `vendor/bin/phpcbf -ps ./temp/yourfilename.php --standard=Yoast --suffix=.fixed`.
    This will create a copy of your test file named `yourfilename.php.fixed`.
    Pro-tip: you can use the `--sniffs=Yoast.Category.SniffName` addition to the command for this step too.
11. Examine the fixes made and expect them to be incorrect.
12. Check out the branch containing the change.
13. Repeat step 10 and 11. The sniff should now fix things correctly.

If you like, you can now delete the `temp` directory and your test file(s), but leaving them in place for the next round of testing shouldn't do any harm.

</details>

<details>
  <summary>Step by step: acceptance test based on a existing plugin code base</summary>

1. Create a simple PHP file with some code which should be flagged and some code which shouldn't be flagged (or adjust an existing file).
2. Save this file somewhere in the plugin.
    Do not commmit this file (or the changes made to an existing file)!
3. Run `vendor/bin/phpcs --report=full,source`.
    This should fail in the expected places (see the commit message of the change for what to expect), i.e. things not getting flagged which should get flagged or things getting flagged, which shouldn't get flagged.
    Pro-tip: if the test is just for a single sniff, you can limit the output to just that sniff by adding `--sniffs=Yoast.Category.SniffName` (replace `Category` and `SniffName` with the applicable names).
4. Run `composer update yoast/yoastcs:"dev-featurebranchname"` from the root of the plugin in which you are testing the change.
    I.e. a branch in YoastCS named `feature/sniffname-change` should be referenced as `dev-feature/sniffname-change`.
    Again: do not commit this change to the `composer.json` and `composer.lock` files!
5. Run the command from [3] again. The sniff should now flag things correctly.

If the sniff change includes changes to/adding of auto-fixers, the fixing should also be tested.

6. Reset the changes to the `composer.json` and `composer.lock` file, but do **not** reset the changes to your test file.
7. Run `composer install`.
8. Run `vendor/bin/phpcbf --suffix=.fixed`.
    This will create a copy of your test file with `fixed` at the end. So a file originally named `src/admin/classname.php` will now have a second copy named `src/admin/classname.php.fixed`.
    Pro-tip: you can use the `--sniffs=Yoast.Category.SniffName` addition to the command for this step too.
9. Examine the fixes made and expect them to be incorrect.
10. Run `composer update yoast/yoastcs:"dev-featurebranchname"` again from the root of the plugin in which you are testing the change.
    Again: do not commit this change to the `composer.json` and `composer.lock` files!
11. Repeat step 8 and 9. The sniff should now fix things correctly.

Clean up: make sure to reset all the file changes made during testing!

</details>

---

### Gotcha's when testing sniff changes

#### Public properties

Some sniffs will behave differently based on the value of the sniff's `public` properties.
These properties can be set [from a custom ruleset](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Annotated-Ruleset) or in a test situation, in-line using `// phpcs:set Yoast.Category.SniffName propertyName propertyValue`.

If the results you are getting when testing are different from what you expected, first thing to do is to check whether the sniff has `public` properties, what those properties are set to (in your custom ruleset or in a test file in-line) and whether that setting is interferring with the results.

#### Severity

The default severity level in PHPCS to show notices is `5`.

Occassionaly (not very often), certain notices will be given a lower severity to prevent "noise" in the CI, while still allowing for something to be (manually) checked.

If the results you are getting when testing are different from what you expected, check if the `$severity` is set for the error message you are missing (fifth parameter in a call to `addError()` or `addWarning()`) and if so, add `--severity=1` to the command line command you are running to get those messages to show up.


### For regular sniff testers

If you regularly test sniffs and/or want to contribute to YoastCS with sniff additions or sniff changes, all the setup needed for the above testing methods can get tedious.

In that case, I'd recommend adding the `vendor/squizlabs/php_codesniffer/bin` directory within `YoastCS` to your system path. This should make the `phpcs` and `phpcbf` commands available anywhere on your system. You can then use those commands anywhere and whichever branch you have checked-out in your YoastCS clone will be used to run the requested scans.

In effect, this means that if you run a scan using `phpcs` within a plugin directory, the ruleset of that plugin will still be respected, but instead of using the YoastCS install in the `vendor` directory of the plugin, your cloned YoastCS install will be used.

If you also (want to) contribute to WordPressCS, PHPCS and/or other standards, contact [@jrfnl](http://github.com/jrfnl/) to discuss an even more robust setup.
