# Development principles (pstack-inspired)

Adapted from https://github.com/cursor/plugins/tree/main/pstack — less code, higher quality,
verify before claiming done.

The full rules live in `AGENTS.md`, `.cursor/rules/`, and `.claude/skills/`. This file keeps the
short version for quick reference.

- **Go deep first.** Read the code and reproduce the problem before changing it.
- **Write less.** Prefer deletion to abstraction. No speculative config or layers.
- **Prove it.** A test, an eval, or a smoke check. "Should work" is not a result.
- **Small commits.** One concern each, imperative subject.
- **No slop.** Name things for what they do; match the surrounding style; leave no dead code.
- **Postgres only**, including tests. pgvector is available.
- **Tools first.** A game capability lands as a `GameTool`; the helper and UI get it for free.
- **Server authoritative.** Numbers are decided in PHP, displayed in React.
- **Docker only.** `./dev.sh up|down|test|check|smoke|fresh`.
- **Secrets stay out of git.** `AGENT_API_KEY` lives in `.env`.
