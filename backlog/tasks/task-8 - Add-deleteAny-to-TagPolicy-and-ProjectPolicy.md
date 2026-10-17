---
id: TASK-8
title: Add deleteAny to TagPolicy and ProjectPolicy
status: To Do
assignee: []
created_date: '2026-10-17 12:43'
labels:
  - bug
dependencies: []
priority: high
type: bug
ordinal: 17000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
TagPolicy and ProjectPolicy define delete but not deleteAny. Filament therefore allows the DeleteBulkAction on the list tables for any admin, including users without tags.delete or projects.delete. Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [ ] #1 Bulk delete on Tags/Projects lists is hidden and blocked without the delete permission
<!-- AC:END -->
