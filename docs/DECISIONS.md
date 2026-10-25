# Decisions

One line per durable decision. Newest at the top. Do not rewrite history; supersede instead.

| Date | Decision | Why |
| --- | --- | --- |
| 2026-10-25 | Postgres for dev, test, and prod; no SQLite | Postgres-specific features (jsonb, pgvector, partial indexes) must be exercised by tests. |
| 2026-10-25 | `pgvector/pgvector:pg16` image | Embeddings/RAG are planned; the extension should exist from day one. |
| 2026-10-25 | Laravel + Inertia + React, one image | One app to run and reason about; Inertia removes API boilerplate while keeping React. |
| 2026-10-25 | React over Vue/Svelte | Strongest ecosystem for agentic chat UIs; reversible if needed. |
| 2026-10-25 | Game capability lands as a `GameTool` first | Keeps Sage and the UI on one source of truth. |
| 2026-10-25 | Heuristic helper when no LLM key is set | The app must work offline and in CI; tools are still real. |
| 2026-10-25 | Tests use a separate `conditiongame_test` database | Never risk dev data; RefreshDatabase runs migrations once and wraps each test. |
