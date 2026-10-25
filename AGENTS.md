# ConditionGame — working agreement

This file is the first thing any agent or developer reads. It is deliberately opinionated.

## What this is

A browser game whose defining feature is an in-game helper, **Sage**, who can read and act on
live game state through tools. The game is a tactical roguelite about **conditions** (burn,
chill, poison, stun) rather than raw damage. See `docs/spec/GAME_VISION.md`.

## Non-negotiables

1. **Postgres only.** No SQLite, not even in tests. If it doesn't work on Postgres, it doesn't
   work. pgvector is available.
2. **One app.** Laravel + Inertia + React. No second API service. No client state library
   unless a real problem proves it necessary.
3. **Every game capability is a tool first.** The chat is a view over the tool registry, never a
   parallel implementation. See `docs/AGENTIC_DESIGN.md`.
4. **Server is authoritative.** HP, damage, loot, and conditions are decided in PHP. The UI
   displays; it does not decide.
5. **No invented data.** Sage reads through tools. If a number isn't from the database, it
   doesn't exist.
6. **Docker is the only supported runtime.** If it only works on your host, it is broken.
7. **Prove it.** Every behavior change ships with a test, an eval, or a smoke check. "Should
   work" is not a result.

## Commands

```bash
./dev.sh up          # build + start app and postgres
./dev.sh down        # stop
./dev.sh logs        # follow logs
./dev.sh test        # backend suite on Postgres (isolated test DB)
./dev.sh test --filter=HelperEvalTest
./dev.sh check       # pint + phpstan + tests + eslint + tsc + prettier + build
./dev.sh smoke       # real HTTP checks against the running app
./dev.sh fresh       # reset dev data
./dev.sh artisan ... # any artisan command
```

`./dev.sh check` is the definition of done. Do not report work as finished when it is red.

## How to work (the loop)

1. **Discover** — understand the real goal before writing code. Use `docs/DISCOVERY.md` when the
   request is vague, and say what you assume. Non-technical owners are expected: translate.
2. **Specify** — if the change affects rules or player experience, update `docs/spec/`. One
   paragraph and acceptance criteria beat a design doc nobody reads.
3. **Slice** — smallest vertical piece that a player can see. One tool, one endpoint, one panel.
4. **Implement** — match surrounding style, delete more than you add where possible.
5. **Prove** — unit/feature tests, agent evals for helper behavior, smoke for integration.
6. **Report** — what changed, what you verified, what you did not. Flag tradeoffs.

## Who you are working with

Assume the owner may be non-technical or a junior developer. Therefore:

- Ask about **player experience** and constraints, not frameworks.
- Offer **two or three concrete options** with a recommendation and a reason.
- Explain jargon once, in one sentence, then use it normally.
- Never hand over a broken environment. Run the app and the checks.
- Protect them from irreversible choices: data loss, paid services, secret leakage.
- When they ask for something that will hurt the project, say so plainly and offer the better path.

## Guardrails

- Secrets only in `.env` (ignored). Never commit an API key.
- Tools are read-only until a write path has explicit intent and confirmation.
- Validate tool arguments server-side even if the model is trusted.
- Cap agent loops (`config/agent.php`), cap message length, log tool calls.
- No destructive migration without a backup path.
- New dependency? Justify it in the PR description.

## Map

```
app/Game/               agent + tool registry
app/Game/Tools/         one class per game capability
app/Http/Controllers/   thin HTTP layer
app/Models/             Eloquent domain
resources/js/pages/     Inertia pages (React)
docs/spec/              what the game is, in plain language
docs/                   design + workflow
tests/{Unit,Feature,Eval}
scripts/check.sh        all quality gates
```
