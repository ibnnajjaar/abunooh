---
id: TASK-1.6
title: 'Tests: Activities resource'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:57'
labels:
  - testing
dependencies: []
parent_task_id: TASK-1
ordinal: 7000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
The read-only activity log resource has no tests.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 List test covers records, filters and search
- [ ] #2 View test covers the infolist and the ChangeLogList embed
<!-- AC:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added ListActivitiesTest (ordering, ip search, causer/subject/date filters) and ViewActivityTest (infolist state, ChangeLogList embed). Todos link to TASK-2 and TASK-7. Verified: --group=activities gives 7 passed, 3 todos.
<!-- SECTION:FINAL_SUMMARY:END -->
