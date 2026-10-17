---
id: TASK-2
title: 'Add policies for Posts, Roles and Activities resources'
status: To Do
assignee: []
created_date: '2026-10-17 12:42'
labels:
  - bug
dependencies: []
priority: high
type: bug
ordinal: 11000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
PostResource, RoleResource and ActivityResource have no policy classes. Filament therefore allows every action to any active admin, even though PermissionsSeeder defines posts.* and role.* permissions. Found while writing the test suite (TASK-1); the related permission tests are ->todo().
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [ ] #1 Users without posts.*/role.* permissions are forbidden from the matching pages and actions
- [ ] #2 Activity log access is restricted by permission
<!-- AC:END -->
