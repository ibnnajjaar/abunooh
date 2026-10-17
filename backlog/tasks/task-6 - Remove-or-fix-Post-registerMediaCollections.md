---
id: TASK-6
title: 'Remove or fix Post::registerMediaCollections'
status: To Do
assignee: []
created_date: '2026-10-17 12:42'
labels:
  - bug
dependencies: []
priority: low
type: bug
ordinal: 15000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Post::registerMediaCollections calls the undefined method registerGraphifyMediaCollection. Post does not implement HasMedia, so it is never invoked today, but calling it fatals. Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [ ] #1 Post has no method calling an undefined method
<!-- AC:END -->
