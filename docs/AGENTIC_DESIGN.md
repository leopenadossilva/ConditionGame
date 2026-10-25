# Agentic Design

## Principle

Every piece of game functionality the helper can talk about should be reachable as a tool.
The chat is a view over tools, not a second implementation of the game.

```
React (Sage chat) -> POST /api/agent/chat -> AgentService -> ToolRegistry -> Tool -> Eloquent
```

## Tool contract

`App\Game\Tools\GameTool`:

- `name()` — stable, `resource_action` (`enemies_get`)
- `description()` — what the agent reads to decide when to call it
- `parameters()` — JSON Schema for arguments
- `handle(array $arguments)` — returns JSON-serializable data

`ToolRegistry` produces both the LLM tool definitions and the UI catalog from those
methods, so a new tool is automatically visible to the agent and to the player.

## Agent loop

1. System prompt pins the helper to live data ("never invent enemies or stats") and the
   current player summary.
2. The model gets the tool definitions and may emit tool calls.
3. `AgentService` executes each call through the registry, appends the JSON result as a
   `tool` message, and loops (bounded by `agent.max_steps`).
4. Without an API key, a keyword router calls the same tools deterministically.

Tool calls are returned to the UI and rendered under each reply, so the player can see
what the helper actually looked up.

## Rules for new features

- Add game capability as a tool first; the chat gets it for free.
- Keep tools read-only until a write path has explicit intent + confirmation.
- Validate arguments inside the tool, not only at the HTTP edge.
- Never let the model compute numbers the DB already owns.
