# Agentic Design

## Principle

Every piece of game functionality the helper can talk about is reachable as a tool. The chat is a
view over tools, never a second implementation of the game.

```
React (Sage chat) -> POST /api/agent/chat -> AgentService -> ToolRegistry -> Tool -> Eloquent -> Postgres
```

## Tool contract

`App\Game\Tools\GameTool`:

- `name()` — stable, `resource_action` (`enemies_get`, `enemy_get`)
- `description()` — what the agent reads to decide when to call it
- `parameters()` — JSON Schema for arguments
- `handle(array $arguments)` — returns JSON-serializable data

`ToolRegistry` produces both the LLM tool definitions and the UI catalog from those methods, so a
new tool is automatically visible to the agent and to the player.

### Current tools

| Tool | Arguments | Returns |
| --- | --- | --- |
| `enemies_get` | optional `max_level` | all enemies with stats and rewards |
| `enemy_get` | `name` (partial ok) | one enemy or a structured error |
| `player_get` | — | player HP, level, XP, gold |

## Agent loop

1. System prompt pins Sage to live data ("never invent enemies or stats") and summarizes the player.
2. The model receives tool definitions and may emit tool calls.
3. `AgentService` executes each call through the registry, appends the JSON result as a `tool`
   message, and loops (bounded by `agent.max_steps`, default 5).
4. Without an API key, a keyword/token router calls the same tools deterministically.

Tool calls are returned to the UI and rendered under each reply, so the player sees what was
actually looked up. That is the trust mechanism: no invisible claims.

## Trust and safety

- Arguments are validated inside the tool. The model is not trusted.
- Tools stay **read-only** until a write path carries explicit player intent plus confirmation.
- Replies that mention a number must be traceable to a tool result. The eval suite enforces tool
  selection; it is the regression net for hallucination.
- LLM cost is capped by `max_steps` and message length. Per-player rate limits come before any
  public launch.
- The heuristic path must never fabricate data — it queries the same tools.

## Adding a capability

1. Write the rule in `docs/spec/CONDITIONS.md` or `MVP.md`.
2. Add the domain migration/model change.
3. Add the tool and register it in `AppServiceProvider`.
4. Add the HTTP surface only if the UI needs it directly.
5. Add unit/feature tests, an eval case, and a smoke check if routes changed.
6. Run `./dev.sh check`.
