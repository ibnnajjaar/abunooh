---
id: TASK-1.8
title: 'Tests: public web pages'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:57'
labels:
  - testing
dependencies: []
parent_task_id: TASK-1
ordinal: 9000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
The home, post show and projects pages are only lightly tested.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Home, post show and projects pages are covered for published, unpublished and future content and for ordering
<!-- AC:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Moved HomeTest to Web/ and extended it (hidden draft/scheduled/page posts, ordering). Added PostShowTest (post vs page views, 404s) and extended ProjectsTest (unpublished hidden, year ordering). Verified: tests/Feature/Web gives 18 passed.
<!-- SECTION:FINAL_SUMMARY:END -->
