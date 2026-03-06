Release checklist
===========================================================

## Pre-release

- [ ] If no upcoming versioned milestone exists:
    - [ ] Determine what the version number should be for the release and rename the `Next release` milestone to that version number.
        This project uses [semantic versioning](https://semver.org/) to determine the version of the next release.
    - [ ] Move anything that is still open in the `Next release` milestone to a new `Next release` milestone.

## Release

- [ ] Checkout `develop`:
    ```bash
    git checkout develop
    ```
- [ ] Make sure the branch is up to date with `main`:
    ```bash
    git merge origin/main
    ```
- [ ] Update version, date and add changelog entries in `CHANGELOG.md`.
    - Guidelines: [Keep a CHANGELOG](https://keepachangelog.com/).
    - Verify that a release link at the bottom of the `CHANGELOG.md` file has been added.
- [ ] Push branch to origin:
    ```bash
    git push origin develop
    ```
- [ ] Make sure all CI builds are green.
- [ ] Check out the `main` branch:
    ```bash
    git checkout main
    ```
- [ ] Merge `develop` into `main`:
    ```bash
    git merge --no-ff develop
    ```
- [ ] Create a new tag:
    ```bash
    git tag -a [new-version]
    ```
- [ ] Push the commit and tag to the remote:
    ```bash
    git push origin main --tags
    ```
- [ ] Create a new release on GitHub and add the changelog entry to it.
    - Add the relevant links to the bottom of the changelog to make sure all texts between `[]` in changelog entries become links. 
      - For example: `[PHP_CodeSniffer]` only becomes a link when `[PHP_CodeSniffer]: https://github.com/squizlabs/PHP_CodeSniffer/releases` is present as well.
- [ ] Merge `main` into `develop`:
    ```bash
    git checkout develop
    git merge origin/main
    git push origin develop
    ```
- [ ] Close the milestone.
- [ ] Create a new `Next release` milestone if it does not yet exist.
- [ ] If any open PRs/issues that were milestoned for the release did not make it into the release, update their milestone.

## Announce

- [ ] Celebrate the new release 🎉
- [ ] Add a message to the `#yoastcs` Slack channel on the [Yoast Slack](https://yoast.slack.com/) to announce the new release:
```
We just released YoastCS x.y.z
Changelog: https://github.com/Yoast/yoastcs/releases/tag/x.y.z
```