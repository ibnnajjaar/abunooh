---
id: TASK-12
title: Upgrade maatwebsite/excel to 4.x for PHP 8.5
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 13:30'
updated_date: '2026-10-17 13:31'
labels: []
dependencies: []
ordinal: 21000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
`composer update` fails on the PHP 8.5 runtime the site uses (.lerd php_version 8.5): maatwebsite/excel 3.1.x requires phpoffice/phpspreadsheet ^1.30, and phpspreadsheet 1.30.x declares php <8.5.0. maatwebsite/excel 4.x depends on phpspreadsheet ^5.9 which supports PHP 8.5. Only config/excel.php references the package in app code.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 `composer update` resolves and installs without platform errors on PHP 8.5
- [x] #2 composer.json requires maatwebsite/excel ^4.0
- [x] #3 config/excel.php is compatible with the 4.x package
- [x] #4 Test suite passes after the upgrade
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Require maatwebsite/excel ^4.0 with -W so phpspreadsheet moves to 5.x
2. Diff config/excel.php against the 4.x package config and update it
3. Run the test suite
<!-- SECTION:PLAN:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
composer require maatwebsite/excel:^4.0 -W also refreshed other locked packages within their constraints (e.g. laravel/framework 13.21 -> 13.37), matching what composer update would do. phpspreadsheet 1.30.6 -> 5.10.0. config/excel.php replaced with the 4.x published config (same values; adds source_handlers and env overrides). Verified: composer update --dry-run resolves on PHP 8.5.11; php artisan test 261 passed.
<!-- SECTION:NOTES:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Upgraded maatwebsite/excel ^3.1 -> ^4.0 (phpspreadsheet 5.x) so composer resolves on PHP 8.5, and synced config/excel.php with the 4.x config. Verified with composer update --dry-run and the full test suite (261 passed).
<!-- SECTION:FINAL_SUMMARY:END -->
