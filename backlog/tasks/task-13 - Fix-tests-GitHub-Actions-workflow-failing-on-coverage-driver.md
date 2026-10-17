---
id: TASK-13
title: Fix tests GitHub Actions workflow failing on coverage driver
status: In Progress
assignee:
  - '@claude'
created_date: '2026-10-17 14:19'
updated_date: '2026-10-17 14:19'
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
- [ ] #1 Pushes whose commit message does not start with "coverage" run the test suite without requiring a coverage driver
- [ ] #2 Commits starting with "coverage" still generate clover.xml and the coverage badge
- [ ] #3 The tests workflow passes on main
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Make --coverage/--coverage-clover flags conditional on the same 'coverage' commit-message check that enables xdebug
2. Verify YAML and push; confirm workflow run passes
<!-- SECTION:PLAN:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
Made pest coverage flags conditional on the 'coverage' commit-message prefix, matching the xdebug setting. Local suite: 261 passed. Pending: push and confirm CI run.
<!-- SECTION:NOTES:END -->
