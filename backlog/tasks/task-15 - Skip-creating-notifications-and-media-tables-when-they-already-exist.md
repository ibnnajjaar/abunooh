---
id: TASK-15
title: Skip creating notifications and media tables when they already exist
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 18:17'
updated_date: '2026-10-17 18:18'
labels: []
dependencies: []
ordinal: 24000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Deploying TASK-14 to production failed: `2025_08_20_202632_create_notifications_table` stopped with "Table notifications already exists". The 2026-10-14 dump (database.sql.gz) had no notifications or media table, but production does have notifications, so the live schema is ahead of the dump. The migrations before it ran and are now recorded; notifications, media, add_fields_to_general_settings and the TASK-14 column migration are still pending there.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Running migrate where notifications and/or media already exist completes without errors and leaves those tables unchanged
- [x] #2 Fresh migrate on an empty database still creates notifications and media
- [x] #3 The legacy-schema test covers existing notifications and media tables
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Return early from the notifications and media create migrations when the table exists (same pattern as TASK-14).
2. Extend the legacy-schema test with a case where notifications and media already exist.
3. Verify on MySQL against the production state: dump + users ALTER, first six pending migrations recorded, notifications table present; run migrate. Run the full suite.
<!-- SECTION:PLAN:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
The 2026-10-14 dump is older than production: production has a notifications table the dump lacks. Guarded media too, since its state on production is unknown. MySQL check: dump + users ALTER + existing notifications table -> all 10 pending migrations ran, rows unchanged, re-run "Nothing to migrate". On production the first six migrations are already recorded, so the next migrate runs notifications (skipped), media, add_fields_to_general_settings and add_missing_legacy_schema_columns. Full suite: 266 passed.
<!-- SECTION:NOTES:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Notifications and media create migrations now skip when the table already exists, fixing the production deploy failure. Verified with a new legacy-schema test case, a MySQL run against the dump plus an existing notifications table, and the full suite (266 passed).
<!-- SECTION:FINAL_SUMMARY:END -->
