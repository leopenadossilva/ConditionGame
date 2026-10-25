#!/usr/bin/env bash
set -e

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_ROOT"

case "${1:-up}" in
  up)
    echo "Starting containers..."
    docker compose up -d --build
    echo "Containers starting. Use 'docker compose logs -f' to view logs."
    ;;
  down)
    echo "Stopping containers..."
    docker compose down
    ;;
  restart)
    echo "Restarting containers..."
    docker compose down && docker compose up -d --build
    ;;
  logs)
    docker compose logs -f "${@:2}"
    ;;
  ps|status)
    docker compose ps
    ;;
  *)
    echo "Usage: ./dev.sh [up|down|restart|logs|ps]"
    exit 1
    ;;
esac
