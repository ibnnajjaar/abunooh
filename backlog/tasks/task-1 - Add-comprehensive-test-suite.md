---
id: TASK-1
title: Add comprehensive test suite
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:57'
labels:
  - testing
dependencies: []
type: task
ordinal: 1000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Test coverage was partial (28 tests, 1 failing). The suite should cover every resource, page, controller, model, service, policy, helper and enum, so regressions get caught. Filament resources follow the tests/Feature/Admin/Tags pattern of one file per CRUD operation. Known bugs are recorded as ->todo() tests, with separate bug tasks; app code is not changed.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Full suite passes with php artisan test
- [ ] #2 Every Filament resource has separate Create/List/View/Edit/Delete test files where the operation exists
- [ ] #3 Known bugs are captured as todo tests referencing their bug task
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
Follow approved plan at ~/.claude/plans/now-go-through-the-swirling-fiddle.md: per-resource CRUD test files (1.1-1.6), admin pages/auth (1.7), web (1.8), domain unit tests (1.9). Bugs recorded as todo tests linked to TASK-2..TASK-7.
<!-- SECTION:PLAN:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Test suite grown from 28 tests (1 failing) to 224 passing and 26 todo (832 assertions). Each Filament resource has one file per CRUD operation, following the Tags pattern. Bugs found while writing tests were filed as TASK-2 to TASK-11 and are recorded as ->todo() tests that reference them. No app code changed. Verified with php artisan test and vendor/bin/pint --test tests.
<!-- SECTION:FINAL_SUMMARY:END -->
