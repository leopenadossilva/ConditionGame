# Glossary

Plain-language definitions for anyone joining the project.

- **Agent / helper** — Sage. A program that can answer questions and use tools to look things up.
- **Tool** — a named capability the helper can call, like `enemies_get`. It has a description, a
  list of inputs, and a result. Tools are how the helper touches the game.
- **Tool call** — one use of a tool. The UI shows these as small chips so you can see what the
  helper actually checked.
- **Eval** — a test for the helper: a fixed question and the tools we expect it to use.
- **Inertia** — the bridge that lets a Laravel app render React pages without building a separate
  JSON API for every screen.
- **Postgres** — the database. All game state lives here. We do not use SQLite at all.
- **pgvector** — a Postgres extension for storing vectors (numeric fingerprints of text). It will
  power "search the lore" features later.
- **Migration** — a versioned change to the database structure.
- **Seeder** — sample starting data (player, enemies) inserted on first run.
- **Condition** — a lasting state on a fighter: Burn, Chill, Poison.
- **Reaction** — a bonus effect caused by combining conditions in the right order.
- **Smoke test** — a few real requests against the running app to prove it is alive.
- **Quality gate** — an automated check that must pass before work counts as done
  (`./dev.sh check`).
- **Slice** — the smallest complete piece of a feature a player can see.
- **MVP** — the smallest version worth showing to a player.
