---
id: TASK-3
title: Fix UserPolicy permission names that do not exist
status: To Do
assignee: []
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:48'
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
- [ ] #1 UserPolicy only references seeded permissions
- [ ] #2 EditUser delete action works for permitted users
<!-- AC:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
Impact confirmed: the EditUser page throws PermissionDoesNotExist for every user while rendering its DeleteAction, so users cannot be edited at all. tests/Feature/Admin/Users/EditUserTest.php and DeleteUserTest.php work around this by creating user.delete in beforeEach; remove that once fixed.
<!-- SECTION:NOTES:END -->
