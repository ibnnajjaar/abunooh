---
id: TASK-10
title: 'Fix creating posts from admin: author_id not saved'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:46'
updated_date: '2026-10-17 13:12'
labels:
  - bug
dependencies: []
priority: high
type: bug
ordinal: 19000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Creating a post through the Filament CreatePost form fails with "NOT NULL constraint failed: posts.author_id". author_id is missing from Post::$fillable, so the selected author is discarded during mass assignment. The form also does not require an author or default to the current user. Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Creating a post via the admin form persists the selected author
- [x] #2 Author is required or defaults to the logged-in admin
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Add author_id to Post::$fillable.
2. Make the author Select required, defaulting to the logged-in admin (web_admin guard).
3. Replace the TASK-10 todos (create, change author) with tests, add default author and required tests, then run the suite.
<!-- SECTION:PLAN:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added author_id to Post::$fillable. The author Select is now required and defaults to the logged-in web_admin user. Creating a post persists the author and editing can change it. Todos replaced with tests, plus default-author and required tests. Verified: full suite gives 239 passed, 18 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
