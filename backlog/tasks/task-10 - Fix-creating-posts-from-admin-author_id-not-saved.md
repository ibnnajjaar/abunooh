---
id: TASK-10
title: 'Fix creating posts from admin: author_id not saved'
status: To Do
assignee: []
created_date: '2026-10-17 12:46'
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
- [ ] #1 Creating a post via the admin form persists the selected author
- [ ] #2 Author is required or defaults to the logged-in admin
<!-- AC:END -->
