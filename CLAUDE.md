
<!-- BACKLOG.MD GUIDELINES START -->
<!-- backlog.md-instructions-version: 1.52.0 -->
<CRITICAL_INSTRUCTION>

## Backlog.md Workflow

This project uses Backlog.md for task and project management.

**At the beginning of each conversation in this project, run `backlog instructions overview` before answering or taking action. Re-read it only if you have not read it yet in the current conversation.**

Use the overview to decide whether to search, read, create, or update Backlog tasks.

Before task lifecycle actions, read the matching detailed guide:
- `backlog instructions task-creation` before creating or splitting tasks
- `backlog instructions task-execution` before planning, changing status or assignee, adding a plan or implementation notes, or implementing task work
- `backlog instructions task-finalization` before checking acceptance criteria, writing final summaries, or moving tasks to terminal statuses

Use `backlog <command> --help` before running unfamiliar commands. Help shows options, fields, and examples.

Do not edit Backlog task, draft, document, decision, or milestone markdown files directly. Use the `backlog` CLI so metadata, relationships, and history stay consistent.

</CRITICAL_INSTRUCTION>
<!-- BACKLOG.MD GUIDELINES END -->

## Project Rules

### Backlog Task Per Code Change

- Every change that requires a code update must have its own Backlog task. Create it with the `backlog` CLI (following `backlog instructions task-creation`) before writing any code, unless a matching task already exists.
- One task per change: do not bundle unrelated changes into a single task.
- Track the work through the task lifecycle (`task-execution`, then `task-finalization`) and reference the task ID in commit messages.
- Questions, explanations, and investigations that don't change code do not need a task.

### DevOps Server Is Out of Scope

- Do not access, connect to, or modify the DevOps server (deployments, server configuration, or infrastructure). This is outside the project's concern; limit work to this repository.
