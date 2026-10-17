---
id: TASK-1.7
title: 'Tests: Settings page, panel access, admin login and Socialite'
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:42'
updated_date: '2026-10-17 12:57'
labels:
  - testing
dependencies: []
parent_task_id: TASK-1
ordinal: 8000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
SettingsTest fails because of RichEditor <p> wrapping. Panel access, the login page and the Socialite redirect/error paths are not tested.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 SettingsTest passes and covers read-only mode without settings.update
- [ ] #2 Panel access tests cover guest, inactive and active users
- [ ] #3 Socialite tests cover redirect, unsupported provider, callback exception and inactive user
<!-- AC:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Moved SettingsTest to Admin/ and fixed the RichEditor <p> expectation. Added tests for mount state, validation and read-only mode. Added PanelAccessTest, AdminLoginTest (Google button, password form toggle, password login) and extended SocialiteControllerTest (redirect, unsupported provider, redirect and callback exceptions, inactive user, preserved verification). All pass.
<!-- SECTION:FINAL_SUMMARY:END -->
