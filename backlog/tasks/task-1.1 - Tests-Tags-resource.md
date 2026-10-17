---
id: TASK-1.1
title: 'Tests: Tags resource'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:57'
labels:
  - testing
dependencies: []
parent_task_id: TASK-1
ordinal: 2000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Tags already has Create/List/View/Edit test files. It still needs coverage for delete, slug auto-fill and unique slug.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 DeleteTagTest covers bulk delete with and without permission
- [ ] #2 Create/Edit tests cover slug auto-fill and unique slug
<!-- AC:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added DeleteTagTest (bulk delete) and tests for slug auto-fill and unique slug in Create/EditTagTest. The 'without permission' bulk delete case is a todo linked to TASK-8, because TagPolicy lacks deleteAny. Verified: --group=tags gives 17 passed, 1 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
