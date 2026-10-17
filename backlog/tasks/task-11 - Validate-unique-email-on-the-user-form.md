---
id: TASK-11
title: Validate unique email on the user form
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:48'
updated_date: '2026-10-17 13:16'
labels:
  - bug
dependencies: []
type: bug
ordinal: 20000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
UserDetailsForm has no unique rule on email. Creating or editing a user with an existing email fails with a database unique constraint error instead of a validation message. Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Duplicate email shows a validation error on create and edit
- [x] #2 Editing a user keeps their own email without error
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Add unique(ignoreRecord: true) to the email field in UserDetailsForm.
2. Replace the TASK-11 todo with create and edit tests (a duplicate is rejected, the user's own email is accepted).
3. Run the suite.
<!-- SECTION:PLAN:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added unique(ignoreRecord: true) to the email field in UserDetailsForm, which both create and edit use. Tests cover a duplicate rejected on create and edit, and keeping one's own email. Verified: full suite gives 257 passed, 3 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
