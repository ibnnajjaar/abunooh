---
id: TASK-8
title: Add deleteAny to TagPolicy and ProjectPolicy
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:43'
updated_date: '2026-10-17 13:15'
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
- [x] #1 Bulk delete on Tags/Projects lists is hidden and blocked without the delete permission
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Add deleteAny (tags.delete / projects.delete) to TagPolicy and ProjectPolicy.
2. Replace the TASK-8 todos with tests that the bulk action is hidden and blocked, and add deleteAny to the policy datasets.
3. Run the suite.
<!-- SECTION:PLAN:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added deleteAny (tags.delete / projects.delete) to TagPolicy and ProjectPolicy, so the list bulk delete is hidden without the delete permission. TASK-8 todos replaced with tests; policy datasets cover deleteAny. Verified: full suite gives 255 passed, 4 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
