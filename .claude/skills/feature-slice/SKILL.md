---
name: feature-slice
description: Implement one vertical slice of game or app functionality, including domain, tool exposure, UI, and tests. Use when adding a capability a player can see or use.
---

# Feature slice

One slice = domain → tool → HTTP → UI → proof. Do not split this across separate sessions.

## Checklist

1. **Domain first.** Migration + model + seeder data if needed. Postgres types only. Money/damage
   are integers; never floats. Server decides, UI displays.
2. **Tool next.** If a player or Sage can talk about it, add a `GameTool` in `app/Game/Tools`:
   `name()` as `resource_action`, `description()` written for a model, `parameters()` JSON Schema,
   `handle()` returning arrays. Register it in `AppServiceProvider`. Validate arguments in the tool.
3. **HTTP only if needed.** Thin controller, `$request->validate`, return JSON or an Inertia page.
   No business logic in controllers.
4. **UI last, small.** Extend the existing page. Reuse spacing, colors, and component shapes
   already in `resources/js/pages/Game.tsx`. Empty, loading, and error states are part of done.
5. **Proof.**
   - Feature test for the endpoint/page
   - Eval case in `tests/Eval` if Sage behavior changed
   - `./dev.sh check` green
   - `./dev.sh smoke` green when the running app is affected

## Rules

- No new dependency for something 30 lines can do.
- No client-side duplicates of server rules.
- No silent catches. If a tool fails, return a structured error the UI can show.
- Delete the old path in the same change. No parallel implementations.
- Update `docs/spec/MVP.md` checkboxes when the slice lands.
