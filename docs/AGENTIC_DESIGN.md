# Agentic Game Design

## Helper Avatar
The helper is an agent that can:
- Chat naturally with the player via smart chat area
- Use tools to inspect and interact with game state
- Guide, inform, and assist gameplay

## Tools
Tools follow a naming pattern like `resource_action`:
- `enemies_get()` - Fetch all enemies
- Future: `player_get()`, `inventory_get()`, `quest_get()`, etc.

## Architecture
- Frontend calls backend API for both game data and agent interactions
- Agent service evaluates requests and can invoke game tools
- Tools are discoverable and composable
