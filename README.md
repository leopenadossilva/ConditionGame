# ConditionGame

An agentic game: a browser game whose content and functionality are reachable by a
tool-using helper avatar. v1 is a single Laravel app (Inertia + React) on Postgres.

## Stack

- **Laravel 13** (PHP 8.4) — routing, domain models, tool layer
- **Inertia + React + TypeScript** — one app, no separate API server
- **Postgres 16** — game state
- **Docker Compose** — `app` + `postgres`, driven by `dev.sh`

## Run it

```bash
./dev.sh up       # build and start app + postgres
./dev.sh down     # stop and remove containers
./dev.sh restart  # down, then up
./dev.sh logs     # follow logs
./dev.sh ps       # container status
./dev.sh sh       # shell inside the app container
./dev.sh artisan ...   # run artisan in the container
./dev.sh test     # backend test suite
./dev.sh fresh    # drop, migrate, seed
```

App: http://localhost:8000

`up` rebuilds the image, so code changes are picked up on the next `up`. The database
lives in the `pg_data` volume and survives restarts.

## The helper (Sage)

The right-hand panel is an agent, not a static FAQ. It answers by calling game tools:

| Tool | Purpose |
| --- | --- |
| `enemies_get` | List enemies with level, HP, rewards (optional `max_level`) |
| `player_get` | Read the player's HP, level, XP, gold |

Tools live in `app/Game/Tools` and are registered in `app/Providers/AppServiceProvider.php`.
Each one implements `App\Game\Tools\GameTool`, so adding a tool means writing one class and
registering it — the HTTP API, the LLM tool schema, and the UI catalog all pick it up.

### Agent modes

- **heuristic** (default, no key): deterministic keyword routing that still calls real tools.
- **llm**: set `AGENT_API_KEY` (and optionally `AGENT_BASE_URL`, `AGENT_MODEL`) to any
  OpenAI-compatible endpoint and Sage runs a bounded tool-calling loop.

Requests go to `POST /api/agent/chat` and return:

```json
{
  "reply": "Enemies out there: Slime (Lv1, 10/10 HP), Goblin (Lv2, 18/18 HP).",
  "tool_calls": [{ "name": "enemies_get", "arguments": {}, "result": { "count": 4 } }],
  "mode": "heuristic"
}
```

## Layout

```
app/Game/            ToolRegistry, AgentService, Tools/*
app/Http/Controllers AgentController, GameController
resources/js/Pages/  Game.tsx (scene + chat)
routes/api.php       /api/agent/chat, /api/game/*
docker/              entrypoint.sh
docs/                design notes
```

## Tests

```bash
./dev.sh test            # whole suite
./dev.sh test --filter=AgentChatTest
```

Covers the page render, tool registry shape, and the agent's tool selection. Tests run on
SQLite in-memory (`phpunit.xml` forces `DB_CONNECTION=sqlite` through both env and server
vars) so they never touch the dev Postgres database.

## Environment

Copy `.env.example` to `.env` for host-side tooling. Compose passes its own values, so
containers do not need a host `.env`. Keep `AGENT_API_KEY` out of git.
