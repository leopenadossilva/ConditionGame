# Development Principles (pstack-inspired)

Adapted from https://github.com/cursor/plugins/tree/main/pstack — less code, higher
quality, verify before claiming done.

## Working rules

- **Go deep first.** Read the existing code and reproduce the problem before changing it.
  The best spec is code.
- **Write less.** Prefer deleting code to adding abstraction. No speculative layers, no
  config nobody sets, no comments that restate the line.
- **Prove it.** Every behavior change ships with a test or a command whose output shows it
  works. "Should work" is not a result.
- **Small, reviewable commits.** One concern per commit, imperative subject.
- **No slop.** Name things for what they do. Match surrounding style. No dead code left
  behind, no TODOs as a substitute for deciding.

## Project-specific

- **Agentic by design.** Game capability lands as a `GameTool` first; the helper and UI
  catalog pick it up automatically. See `docs/AGENTIC_DESIGN.md`.
- **One app.** Laravel + Inertia + React. No second API service, no client-side state
  library unless proven necessary.
- **Docker-first.** `./dev.sh up|down|test` is the only supported workflow; anything that
  only works on the host is a bug.
- **Data belongs to Postgres.** The agent reads through tools; it never invents stats.
- **Secrets stay out of git.** `AGENT_API_KEY` lives in `.env`, which is ignored.
