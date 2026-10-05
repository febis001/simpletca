# Contributing

This is a stub — the full contributing guide follows in a later PR. It already
documents the release and branching model because every pull request depends
on it.

## Workflow

1. One change = one feature branch = one pull request against `master`.
2. All commits and the PR title must follow
   [Conventional Commits](https://www.conventionalcommits.org/) — enforced by CI
   (commitlint). The release automation derives version numbers from them, so a
   non-conforming message breaks releases.
3. CI (lint + static analysis) and commitlint must be green before merge.
4. Never add AI attribution trailers (`Co-authored-by: ...`, `Generated-by: ...`)
   to commits — CI rejects them.

## Release & branching model

Releases are fully automated with
[semantic-release](https://semantic-release.gitbook.io/) (see
`.github/workflows/release.yml`). On every push to a release branch it analyzes
the conventional commits since the last tag, computes the next version, updates
`CHANGELOG.md`, tags the release and creates the GitHub release. Versions live
only in git tags — `composer.json` intentionally has no `version` field.

**Versioning policy: the SimpleTCA major version is bound to the TYPO3 major
version.** SimpleTCA 13.x supports TYPO3 v13, 14.x will support TYPO3 v14, etc.

| Branch   | Purpose                                                        | Releases |
|----------|----------------------------------------------------------------|----------|
| `master` | Current TYPO3 major (e.g. SimpleTCA 13.x while TYPO3 v13 is current) | major/minor/patch |
| `NN.x`   | Maintenance branch for older TYPO3 majors (e.g. `13.x` after v14 lands) | minor/patch only |

### How the major bump happens

When support for a new TYPO3 major lands:

1. Create the maintenance branch for the old major from `master`
   (e.g. `git branch 13.x`).
2. Land a commit on `master` with `BREAKING CHANGE: requires TYPO3 v14` in the
   message — semantic-release bumps `master` to `14.0.0`.
3. Fixes for users of the old TYPO3 version are cherry-picked to the `NN.x`
   branch, which releases `NN.x.y` patches.

Dropping an old TYPO3 version genuinely is a breaking change, so this keeps
semver intact. Please batch ordinary API breaks into the TYPO3-major jump
whenever possible so the two major numbers do not drift apart.

### Commit type → version mapping

| Commit                          | Release |
|---------------------------------|---------|
| `fix:`                          | patch   |
| `feat:`                         | minor   |
| `BREAKING CHANGE:` / `type!:`   | major   |
| `docs/chore/test/ci/refactor/build` | none (unless breaking) |
