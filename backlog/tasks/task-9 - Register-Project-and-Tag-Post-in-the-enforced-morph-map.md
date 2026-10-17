---
id: TASK-9
title: Register Project (and Tag/Post) in the enforced morph map
status: To Do
assignee: []
created_date: '2026-10-17 12:45'
updated_date: '2026-10-17 12:55'
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
- [ ] #1 Projects can be deleted from the edit page and with the bulk action
- [ ] #2 Project, Tag and Post have morph aliases
<!-- AC:END -->

## Implementation Notes

<!-- SECTION:NOTES:BEGIN -->
Project::featured_image (getFirstMediaUrl) also throws ClassMorphViolationException, so any media access on projects is broken.
<!-- SECTION:NOTES:END -->
