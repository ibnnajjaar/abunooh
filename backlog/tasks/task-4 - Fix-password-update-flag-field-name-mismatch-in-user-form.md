---
id: TASK-4
title: Fix password update flag field name mismatch in user form
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 13:17'
labels:
  - bug
dependencies: []
type: bug
ordinal: 13000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
UserPasswordChangeForm defines the checkbox require_password_update_on_next_login, but UserPasswordData::fromArray reads requires_password_update. The flag is never saved. Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Ticking the checkbox sets users.requires_password_update
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Rename the checkbox to requires_password_update, matching the column and UserPasswordData.
2. Make CreateUser use UserService::create, like EditUser, so the flag (not fillable) is saved on create too.
3. Replace the TASK-4 todo with edit and create tests, then run the suite.
<!-- SECTION:PLAN:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
CreateUser now uses UserService::create (the flag is not fillable). Side effect: email_verified_at from the create form is saved too; it was previously dropped by mass assignment.
<!-- SECTION:NOTES:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Renamed the checkbox to requires_password_update, matching the column and UserPasswordData. CreateUser now goes through UserService::create, so the flag and verification date are saved on create as well as edit. Tests cover both flows. Verified: full suite gives 259 passed, 2 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
