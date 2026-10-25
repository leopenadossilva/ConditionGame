#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")"

URL="http://localhost:8000"

usage() {
    cat <<'EOF'
Usage: ./dev.sh <command>

Commands:
  up          Build and start the app + postgres containers
  down        Stop and remove the containers
  restart     down, then up
  logs [svc]  Follow logs (optionally for one service)
  ps          Show container status
  sh          Open a shell in the app container
  artisan ... Run an artisan command in the app container
  test        Run the backend test suite
  fresh       Drop the database, re-migrate, and re-seed
EOF
}

require_docker() {
    if ! command -v docker >/dev/null 2>&1; then
        echo "docker is required." >&2
        exit 1
    fi
}

case "${1:-}" in
    up)
        require_docker
        docker compose up -d --build
        echo "App: $URL"
        ;;
    down)
        require_docker
        docker compose down
        ;;
    restart)
        require_docker
        docker compose down
        docker compose up -d --build
        echo "App: $URL"
        ;;
    logs)
        require_docker
        shift
        docker compose logs -f "$@"
        ;;
    ps|status)
        require_docker
        docker compose ps
        ;;
    sh)
        require_docker
        docker compose exec app bash
        ;;
    artisan)
        require_docker
        shift
        docker compose exec app php artisan "$@"
        ;;
    test)
        require_docker
        shift
        docker compose exec app php artisan test "$@"
        ;;
    fresh)
        require_docker
        docker compose exec app php artisan migrate:fresh --seed --force
        ;;
    *)
        usage
        exit 1
        ;;
esac
