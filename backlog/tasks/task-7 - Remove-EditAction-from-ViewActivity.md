---
id: TASK-7
title: Remove EditAction from ViewActivity
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 13:19'
labels:
  - bug
dependencies: []
priority: low
type: bug
ordinal: 16000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
ViewActivity has an EditAction in its header, but ActivityResource has no edit page. Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 ViewActivity renders without an edit action pointing to a missing page
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Remove the EditAction from ViewActivity's header (the activity log is read-only and has no edit page).
2. Replace the TASK-7 todo with a test and run the suite.
<!-- SECTION:PLAN:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Removed the EditAction from ViewActivity, since ActivityResource has no edit page and the log is read-only. Todo replaced with an assertActionDoesNotExist test. Verified: full suite gives 261 passed, 0 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
