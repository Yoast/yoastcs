Release checklist
===========================================================

## Pre-release

- [ ] If no upcoming versioned milestone exists, and there are merged PRs in the `Next release` milestone:
    - [ ] Determine what the version number should be for the release and rename the `Next release` milestone to that version number.
      This project uses [semantic versioning](https://semver.org/) to determine the version of the next release.
    - [ ] Create a new "Next release" milestone for the release _after_ this one.
    - [ ] Move anything that is still open in the current milestone to the new `Next release` milestone.
- [ ] Verify that any PR merged since the last release has a milestone attached to it.
- [ ] Create a changelog based on everything in the milestone, pull the changelog changes & merge the PR.
    Verify that a release link at the bottom of the `CHANGELOG.md` file has been added.
- [ ] Double-check that nothing was merged into the `main` branch.
    - If there are unreleased commits in `main`, merge `main` into `develop` or cherrypick the commits from main into the `develop` branch.

## Release

- [ ] Open a PR to merge the `develop` branch to `main`.
    - Pro-tip: use this release checklist as the PR description to document that this release checklist has been followed.
- [ ] Merge that PR once all CI checks have passed.
- [ ] Create a release on GitHub, also creating a new tag (targeting `main`) in the process, and copy & paste the changelog to it.
    - Add/edit the relevant links to the bottom of the changelog to make sure all texts between `[]` in changelog entries become links.
    - For example: `[PHP_CodeSniffer]` only becomes a link when `[PHP_CodeSniffer]: https://github.com/squizlabs/PHP_CodeSniffer/releases` is present as well.
- [ ] Publish the release.
- [ ] Fast-forward `develop` to `main`.
- [ ] Close the milestone.

## Announce

- [ ] Post a message in the `#yoastcs` Slack channel on the [Yoast Slack](https://yoast.slack.com/) to announce the new release:
    ```
    We just released YoastCS x.y.z
    Changelog: https://github.com/Yoast/yoastcs/releases/tag/x.y.z
    ```