---
id: TASK-5
title: Fix Post url accessor route name
status: To Do
assignee: []
created_date: '2026-10-17 12:42'
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
- [ ] #1 Post url returns the web.posts.show URL for the slug
<!-- AC:END -->
