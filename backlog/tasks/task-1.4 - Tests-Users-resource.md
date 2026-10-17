---
id: TASK-1.4
title: 'Tests: Users resource'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:57'
labels:
  - testing
dependencies: []
parent_task_id: TASK-1
ordinal: 5000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
The Users resource has no tests. EditUser uses UserService through handleRecordUpdate.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Create/List/View/Edit/Delete test files exist under tests/Feature/Admin/Users
<!-- AC:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added Create/List/View/Edit/DeleteUserTest. EditUser goes through UserService, covering verification preservation and password change. Edit/Delete tests create user.delete in beforeEach as a workaround for TASK-3. Todos link to TASK-3, TASK-4 and TASK-11. Verified: --group=users gives 21 passed, 3 todos.
<!-- SECTION:FINAL_SUMMARY:END -->
