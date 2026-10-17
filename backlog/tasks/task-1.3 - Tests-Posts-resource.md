---
id: TASK-1.3
title: 'Tests: Posts resource'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:57'
labels:
  - testing
dependencies: []
parent_task_id: TASK-1
ordinal: 4000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Posts only has a single list test in PostResourceTest.php.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Create/List/View/Edit/Delete test files exist under tests/Feature/Admin/Posts
- [ ] #2 PostResourceTest.php is removed
<!-- AC:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added Create/List/View/Edit/DeletePostTest under Admin/Posts and removed PostResourceTest.php. Permission denials are todos linked to TASK-2; creating a post and changing the author are todos linked to TASK-10. Verified: --group=posts gives 13 passed, 7 todos.
<!-- SECTION:FINAL_SUMMARY:END -->
