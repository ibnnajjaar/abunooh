---
id: TASK-1.2
title: 'Tests: Projects resource'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:57'
labels:
  - testing
dependencies: []
parent_task_id: TASK-1
ordinal: 3000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Projects only has partial Create/List tests.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Create/List/View/Edit/Delete test files exist and cover permissions, form state, validation, persistence and tags relation
<!-- AC:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added View/Edit/DeleteProjectTest and extended Create/ListProjectsTest: permissions, slug, tags relation, sorting, search, validation. Delete cases are todos linked to TASK-9 (morph map) and TASK-8 (deleteAny). Verified: --group=projects gives 22 passed, 4 todos.
<!-- SECTION:FINAL_SUMMARY:END -->
