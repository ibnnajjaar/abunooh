---
id: TASK-14
title: Make migrations safe to run on the legacy production schema
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 18:10'
updated_date: '2026-10-17 18:14'
labels: []
dependencies: []
ordinal: 23000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
Production was built from another app's migrations (its `migrations` table has names like `create_isims_faculties_table`), so its schema drifted from this repo: `users` lacked `email_verified_at` and `requires_password_update`, `activity_log` lacked `batch_uuid`, `roles`/`permissions` lacked `description`, and `notifications`/`media` were missing. Google login failed with "Unknown column" errors. The overlapping migrations are not recorded under this repo's names, so `php artisan migrate` stops with "table already exists" instead of filling the gaps. Production was patched by hand on 2026-10-17; the repo needs to handle a database restored from the 2026-10-14 dump (database.sql.gz) on its own.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Running `php artisan migrate` on a database restored from database.sql.gz completes without errors
- [x] #2 After that migrate, `users` has `email_verified_at` and `requires_password_update`, `activity_log` has `batch_uuid`, `roles` and `permissions` have `description`, and `notifications` and `media` exist
- [x] #3 Existing rows (users, posts, settings, activity log) are preserved
- [x] #4 Running migrate on an already-reconciled database (e.g. production after the manual SQL fix) is a no-op
- [x] #5 Fresh `migrate` on an empty database still produces the same schema as before
- [x] #6 A test covers migrating from the legacy schema
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Guard the migrations whose tables/columns already exist on the legacy schema so they skip instead of failing: permission tables, activity_log create, event and batch_uuid columns, Google provider columns, and the create_site_settings settings migration.
2. Add a new migration that adds the columns the legacy schema lacks and no older migration would add once its table exists: users.email_verified_at, users.requires_password_update, roles.description, permissions.description (each guarded by hasColumn).
3. Add a Pest test that builds a legacy-shaped schema (dump shape, legacy migration names) in SQLite, runs migrate, and asserts the schema and preserved rows; also assert a fresh migrate still works and re-running is a no-op.
4. Verify against MySQL with database.sql.gz restored into a scratch database, both raw and after the manual SQL fix; run the full test suite.
<!-- SECTION:PLAN:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
Guarding the overlapping migrations (rather than one new migration) is required: they sort before any new migration and would fail with "table already exists" first. The legacy schema also has extra columns (users.allow_admin_panel_access, users.mobile_number, activity_log.attribute_changes) that are left alone. MySQL verification: restored database.sql.gz into a scratch DB; raw dump -> migrate ran all 10 pending migrations, all columns/tables present, row counts unchanged (users 1, posts 10, settings 5, activity 10), user save OK, re-run "Nothing to migrate". With the 2026-10-17 manual SQL fix applied first -> only the new column migration ran, as a no-op. Removing the guards makes 3 of the 4 new tests fail. Full suite: 265 passed. Pint flags pre-existing style in the untouched create_media_table and add_fields_to_general_settings files.

Production had only the users ALTER applied by hand on 2026-10-17 (not the full reconcile SQL). Verified that state on MySQL: dump + users ALTER -> migrate ran all 10 pending migrations, the new migration skipped the existing users columns and added the rest, rows unchanged, re-run "Nothing to migrate".
<!-- SECTION:NOTES:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Made migrations safe on the legacy production schema: guarded the permission, activity log (create/event/batch_uuid), Google provider and site settings migrations so they skip what already exists, and added 2026_10_17_000000_add_missing_legacy_schema_columns for users.email_verified_at/requires_password_update and roles/permissions.description. Verified on MySQL against the restored database.sql.gz (raw and after the manual fix), with a new Pest test (tests/Feature/Database/LegacySchemaMigrationTest.php) and the full suite (265 passed).
<!-- SECTION:FINAL_SUMMARY:END -->
