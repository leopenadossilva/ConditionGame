#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/.."

echo "==> PHP style (Pint)"
vendor/bin/pint --test

echo "==> PHP static analysis (PHPStan)"
vendor/bin/phpstan analyse --no-progress --memory-limit=1G

echo "==> Backend tests (Postgres)"
php artisan test

echo "==> JS lint (ESLint)"
npm run lint

echo "==> TypeScript"
npm run typecheck

echo "==> Formatting (Prettier)"
npm run format:check

echo "==> Asset build"
npm run build
