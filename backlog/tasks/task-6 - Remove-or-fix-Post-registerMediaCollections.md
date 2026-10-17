---
id: TASK-6
title: 'Remove or fix Post::registerMediaCollections'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 13:18'
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
- [x] #1 Post has no method calling an undefined method
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Remove Post::registerMediaCollections: Post does not implement HasMedia and registerGraphifyMediaCollection is defined nowhere.
2. Run the suite.
<!-- SECTION:PLAN:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Removed Post::registerMediaCollections. It called the undefined registerGraphifyMediaCollection, and Post does not use the media library. No references remain (checked with grep). No test added because there is no behaviour to assert. Verified: full suite gives 260 passed.
<!-- SECTION:FINAL_SUMMARY:END -->
