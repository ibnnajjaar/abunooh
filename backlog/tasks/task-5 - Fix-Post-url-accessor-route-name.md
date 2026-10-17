---
id: TASK-5
title: Fix Post url accessor route name
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 13:18'
labels:
  - bug
dependencies: []
type: bug
ordinal: 14000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Post::getUrlAttribute calls route('posts.show'), but the route is registered as web.posts.show, so accessing $post->url throws RouteNotFoundException. Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Post url returns the web.posts.show URL for the slug
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Change Post::getUrlAttribute to route('web.posts.show', $this->slug); the route binds by slug.
2. Replace the TASK-5 todo with a test and run the suite.
<!-- SECTION:PLAN:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Post::url now uses route('web.posts.show', $this->slug). The todo is replaced with a test. Verified: full suite gives 260 passed, 1 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
