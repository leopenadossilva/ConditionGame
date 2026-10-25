# MVP scope

**MVP sentence:** a player can read a region of enemies, ask Sage about them, fight one enemy
with conditions, and win or lose — all persisted in Postgres.

## In scope

### 1. Region screen (exists)
- Player card: name, level, HP, XP, gold.
- Enemy cards: name, level, HP, reward, one-line fiction.
- Sage chat panel with tool-visible replies.

### 2. Enemy knowledge (exists)
- `enemies_get`, `enemy_get`, `player_get` tools.
- Sage answers from live data or says it does not know.

### 3. Conditions (next)
- Three conditions: **Burn**, **Chill**, **Poison**.
- Each has a clear identity:
  - Burn: damage now and at the end of the enemy's turn; spreads to a wet target.
  - Chill: slows; a chilled target takes double Burn damage on application.
  - Poison: ignores armor; grows while the target is not burning.
- Conditions have a stack cap and a visible duration.

### 4. One fight (next)
- Player picks an enemy, enters a fight view.
- Turn structure: player action → enemy action → tick conditions.
- Actions: Strike (small damage), Apply condition, Wait (gain focus).
- Server resolves everything. UI animates results it is told.
- Win: XP and gold. Lose: HP restored to 1, return to region, no reward.

### 5. Sage in combat (next)
- New tools: `fight_state_get`, `condition_explain`.
- Sage can say what a condition does and what to apply next, based on the live fight.

## Acceptance criteria

- [ ] A new player sees 4 enemies and a working chat with no setup beyond `./dev.sh up`.
- [ ] Every Sage reply that mentions a number can be traced to a tool call shown in the UI.
- [ ] A fight can be played to victory and to defeat without a page reload.
- [ ] HP, gold, and XP survive a container restart.
- [ ] `./dev.sh check` and `./dev.sh smoke` are green.
- [ ] A non-technical reader can explain the fight loop after reading `GAME_VISION.md`.

## Out of scope (deliberately)

- Accounts, login, multiplayer.
- Regions beyond the first, shops, inventory.
- Art beyond CSS and emoji-free simple shapes.
- Streaming responses (will come after the fight loop works).
- LLM-authored content. Content is authored by us; Sage only reads it.

## Next after MVP

1. Streaming Sage replies (SSE) with tool-call events.
2. Chat history persisted per player.
3. Region 2 + boss with a condition puzzle.
4. Embedding-based lore search with pgvector.
5. Cost controls and per-player rate limits for the LLM path.
