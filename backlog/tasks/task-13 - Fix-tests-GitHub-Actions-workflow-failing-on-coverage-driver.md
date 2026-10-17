---
id: TASK-13
title: Fix tests GitHub Actions workflow failing on coverage driver
status: In Progress
assignee:
  - '@claude'
created_date: '2026-10-17 14:19'
updated_date: '2026-10-17 14:33'
labels: []
dependencies: []
ordinal: 22000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Every push to main fails the `tests` workflow with "No code coverage driver is available." The workflow always runs `pest --coverage`, but only enables xdebug when the commit message starts with "coverage", so normal pushes have no coverage driver.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [ ] #1 The tests workflow passes on main
- [ ] #2 Every run enables a coverage driver and generates clover.xml
- [ ] #3 Every push to main regenerates and commits .github/coverage.svg
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Always enable xdebug coverage and run pest with --coverage --coverage-clover
2. Run the badge step on every push to main (not on pull requests, which can't push), with contents: write permission for GITHUB_TOKEN
3. Raise the timeout to the coverage-run value
4. Push and confirm the workflow run passes
<!-- SECTION:PLAN:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
Made pest coverage flags conditional on the 'coverage' commit-message prefix, matching the xdebug setting. Local suite: 261 passed. Pending: push and confirm CI run.

Scope changed per user: always run coverage and generate the badge instead of gating on the 'coverage' commit-message prefix.
<!-- SECTION:NOTES:END -->
