---
id: TASK-4
title: Fix password update flag field name mismatch in user form
status: To Do
assignee: []
created_date: '2026-10-17 12:42'
labels:
  - bug
dependencies: []
type: bug
ordinal: 13000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
UserPasswordChangeForm defines the checkbox require_password_update_on_next_login, but UserPasswordData::fromArray reads requires_password_update. The flag is never saved. Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [ ] #1 Ticking the checkbox sets users.requires_password_update
<!-- AC:END -->
