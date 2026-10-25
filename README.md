# ConditionGame

An agentic game: a tactical roguelite about stacking conditions, with a tool-using helper
(**Sage**) who reads live game state. One Laravel app on Postgres.

## Start here

- `AGENTS.md` — how this repo works (non-negotiables, commands, guardrails)
- `docs/spec/GAME_VISION.md` — what the game is, in plain language
- `docs/spec/MVP.md` — current scope and acceptance criteria
- `docs/WORKFLOW.md` — discovery → spec → slice → prove
- `docs/AGENTIC_DESIGN.md` — how Sage and the tool layer work

## Stack

- **Laravel 13** (PHP 8.4) — routing, domain, agent tool layer
- **Inertia + React + TypeScript** — one app, no separate API server
- **Postgres 16 + pgvector** — game state today, embeddings later
- **Docker Compose** — `app` + `postgres`, driven by `dev.sh`

## Run it

```bash
./dev.sh up          # build and start
./dev.sh down        # stop
./dev.sh logs        # follow logs
./dev.sh ps          # status
./dev.sh sh          # shell in the app container
./dev.sh artisan ... # any artisan command
./dev.sh fresh       # reset dev data (drop, migrate, seed)
```

App: http://localhost:8000

`up` rebuilds the image, so code changes are picked up on the next `up`. Data lives in the
`pg_data` volume and survives restarts.

## Quality gates

```bash
./dev.sh check       # pint, phpstan, tests, eslint, tsc, prettier, build
./dev.sh smoke       # real HTTP checks against the running app
./dev.sh test        # backend suite only
./dev.sh test --filter=HelperEvalTest
```

Tests run on **Postgres**, in the `conditiongame_test` database, created automatically. They never
touch the dev database. There is no SQLite path anywhere: if a Postgres-specific feature is used,
the tests exercise it.

Layers:

| Layer | Where | Catches |
| --- | --- | --- |
| Style | Pint | PHP inconsistency |
| Static | PHPStan level 6 (Larastan) | type and assumption errors |
| Unit | `tests/Unit` | registry and pure logic |
| Feature | `tests/Feature` | endpoints, pages, tools against Postgres |
| Eval | `tests/Eval` | Sage picking the wrong tool |
| Client | ESLint, tsc, Prettier | JS/TS defects and drift |
| Smoke | `scripts/smoke.php` | the running stack |

CI runs all of it on every push and pull request.

## The helper

Sage answers by calling tools:

| Tool | Purpose |
| --- | --- |
| `enemies_get` | list enemies with level, HP, rewards (optional `max_level`) |
| `enemy_get` | look up one enemy by partial name |
| `player_get` | read the player's HP, level, XP, gold |

Tools live in `app/Game/Tools` and are registered in `app/Providers/AppServiceProvider.php`.
Adding a tool means one class plus one registration line — the HTTP API, the LLM schema, and the
UI catalog all pick it up.

### Modes

- **heuristic** (no key): deterministic keyword/token routing that still calls real tools.
- **llm**: set `AGENT_API_KEY` (plus optional `AGENT_BASE_URL`, `AGENT_MODEL`) to any
  OpenAI-compatible endpoint for a bounded tool-calling loop.

`POST /api/agent/chat` returns:

```json
{
  "reply": "Enemies out there: Slime (Lv1, 10/10 HP), Stone Golem (Lv5, 60/60 HP).",
  "tool_calls": [{ "name": "enemies_get", "arguments": {}, "result": { "count": 4 } }],
  "mode": "heuristic"
}
```

## Layout

```
AGENTS.md              working agreement (read first)
app/Game/              ToolRegistry, AgentService, Tools/*
app/Models/            Player, Enemy
app/Http/Controllers/  thin HTTP layer
resources/js/pages/    Game.tsx (region + chat)
docs/spec/             vision, MVP, conditions, glossary
docs/                  workflow, agentic design, decisions
tests/{Unit,Feature,Eval}
scripts/check.sh       all quality gates
.github/workflows/ci.yml
docker/                entrypoint, postgres init
```

## Environment

Compose passes the values the app needs; a host `.env` is optional. Copy `.env.example` if you
want to run tooling outside Docker. Keep `AGENT_API_KEY` out of git.
