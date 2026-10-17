---
id: TASK-3
title: Fix UserPolicy permission names that do not exist
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 13:09'
labels:
  - bug
dependencies: []
priority: high
type: bug
ordinal: 12000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
UserPolicy checks user.delete (delete) and user.force_delete (restore/forceDelete). PermissionsSeeder only creates user.delete.own, user.delete.any, user.force_delete.own and user.force_delete.any, so these checks throw PermissionDoesNotExist (e.g. the DeleteAction on EditUser). Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 UserPolicy only references seeded permissions
- [x] #2 EditUser delete action works for permitted users
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Map UserPolicy::delete to user.delete.any, or user.delete.own when the target is the acting user; restore/forceDelete the same way with user.force_delete.*.
2. Remove the beforeEach workaround from the user Edit/Delete tests and turn the TASK-3 todos into real tests (policy + EditUser page).
3. Run the users and policies groups, then the full suite.
<!-- SECTION:PLAN:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
Impact confirmed: the EditUser page throws PermissionDoesNotExist for every user while rendering its DeleteAction, so users cannot be edited at all. tests/Feature/Admin/Users/EditUserTest.php and DeleteUserTest.php work around this by creating user.delete in beforeEach; remove that once fixed.
<!-- SECTION:NOTES:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
UserPolicy delete/restore/forceDelete now use the seeded permissions: user.delete.any or user.force_delete.any for any user, and the .own variants only for the acting user's own account. The EditUser page no longer throws. Removed the test workaround and replaced the todos with policy and EditUser tests. Verified: full suite gives 231 passed, 24 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
