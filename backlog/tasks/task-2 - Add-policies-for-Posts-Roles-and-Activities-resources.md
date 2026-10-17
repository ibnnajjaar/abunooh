---
id: TASK-2
title: 'Add policies for Posts, Roles and Activities resources'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 13:14'
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
- [x] #1 Users without posts.*/role.* permissions are forbidden from the matching pages and actions
- [x] #2 Activity log access is restricted by permission
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Add PostPolicy (posts.*) and RolePolicy (role.*), including deleteAny so bulk delete is gated.
2. Add ActivityPolicy (viewAny/view on a new activity.view permission, seeded by PermissionsSeeder) with no create/update/delete.
3. Replace the TASK-2 todos with permission tests and grant activity.view in the activity tests.
4. Run the full suite.
<!-- SECTION:PLAN:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
Added the activity.view permission to PermissionsSeeder. Existing environments must re-run the seeder (php artisan db:seed --class=PermissionsSeeder) and grant activity.view, role.* and posts.* to admins, or they lose access to these resources.
<!-- SECTION:NOTES:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added PostPolicy (posts.*), RolePolicy (role.*) and ActivityPolicy (activity.view, read-only). Post and Role include deleteAny so bulk delete is gated. Seeded activity.view. TASK-2 todos replaced with forbidden/hidden-action tests. Verified: full suite gives 251 passed, 6 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
