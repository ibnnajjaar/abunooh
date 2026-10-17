---
id: TASK-1.5
title: 'Tests: Roles resource'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:57'
labels:
  - testing
dependencies: []
parent_task_id: TASK-1
ordinal: 6000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
The Roles resource has no tests.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Create/List/View/Edit/Delete test files exist under tests/Feature/Admin/Roles, including permission assignment
<!-- AC:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added Create/List/View/Edit/DeleteRoleTest, including permission assignment, guard_name and users_count. Permission denials are todos linked to TASK-2. Verified: --group=roles gives 12 passed, 5 todos.
<!-- SECTION:FINAL_SUMMARY:END -->
