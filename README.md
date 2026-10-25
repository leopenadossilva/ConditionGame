# ConditionGame v1

Agentic game prototype with HTML/JS frontend, Laravel backend, Postgres DB, dockerized via `dev.sh`.

## Quick Start

```bash
./dev.sh up      # Start all containers
./dev.sh down    # Stop all containers
./dev.sh restart # Restart containers
./dev.sh logs    # View logs
./dev.sh ps      # Check status
```

Services:
- Frontend: http://localhost:8080
- Backend API: http://localhost:8000
- Postgres: localhost:5432

## Agentic Features

- Helper avatar with smart chat interface
- Agent tools (e.g., `enemies_get`)
- Extensible tool system for game state interaction

## Development Practices

Following pstack-inspired principles: rigorous, testable, minimal complexity.
