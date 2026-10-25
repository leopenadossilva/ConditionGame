#!/usr/bin/env bash
set -e

cd /var/www/html

if [ -z "${APP_KEY:-}" ]; then
    [ -f .env ] || : > .env
    grep -q '^APP_KEY=' .env || echo 'APP_KEY=' >> .env
    php artisan key:generate --force --no-interaction >/dev/null
fi

echo "Waiting for the database..."
for attempt in $(seq 1 30); do
    if php artisan migrate --force --no-interaction >/dev/null 2>&1; then
        break
    fi

    if [ "$attempt" -eq 30 ]; then
        echo "Database never became ready." >&2
        exit 1
    fi

    sleep 2
done

if [ "${SEED_DATABASE:-true}" = "true" ]; then
    php artisan db:seed --force --no-interaction >/dev/null 2>&1 || true
fi

exec php artisan serve --host=0.0.0.0 --port=8000 --no-reload
