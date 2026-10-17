---
id: TASK-11
title: Validate unique email on the user form
status: To Do
assignee: []
created_date: '2026-10-17 12:48'
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
- [ ] #1 Duplicate email shows a validation error on create and edit
- [ ] #2 Editing a user keeps their own email without error
<!-- AC:END -->
