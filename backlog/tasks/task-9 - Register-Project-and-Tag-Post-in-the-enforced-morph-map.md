---
id: TASK-9
title: Register Project (and Tag/Post) in the enforced morph map
status: Done
assignee:
  - '@claude'
created_date: '2026-10-17 12:45'
updated_date: '2026-10-17 13:11'
labels:
  - bug
dependencies: []
priority: high
type: bug
ordinal: 18000
---

## Description

<!-- SECTION:DESCRIPTION:BEGIN -->
AppServiceProvider calls Relation::enforceMorphMap without project, tag or post. Project uses InteractsWithMedia, so deleting a project (single or bulk delete in Filament) throws ClassMorphViolationException: "No morph map defined for model [App\Models\Project]". Projects cannot be deleted from the admin panel. Found during TASK-1.
<!-- SECTION:DESCRIPTION:END -->

## Acceptance Criteria
<!-- AC:BEGIN -->
- [x] #1 Projects can be deleted from the edit page and with the bulk action
- [x] #2 Project, Tag and Post have morph aliases
<!-- AC:END -->

## Implementation Plan

<!-- SECTION:PLAN:BEGIN -->
1. Add project, tag and post aliases to the enforced morph map in AppServiceProvider.
2. Check stored morph types: media rows for projects would have been saved under the class name, if any exist.
3. Turn the TASK-9 todos (project delete, bulk delete, tag detach, featured_image) into real tests and run the suite.
<!-- SECTION:PLAN:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
Project::featured_image (getFirstMediaUrl) also throws ClassMorphViolationException, so any media access on projects is broken.
<!-- SECTION:NOTES:END -->

## Final Summary

<!-- SECTION:FINAL_SUMMARY:BEGIN -->
Added post, project and tag aliases to the enforced morph map. Project delete (single and bulk), tag detach and featured_image now work. No data migration was needed: the enforced map meant no project media could have been stored under the class name. Todos replaced with real tests, plus a morph alias test. Verified: full suite gives 236 passed, 20 todo.
<!-- SECTION:FINAL_SUMMARY:END -->
