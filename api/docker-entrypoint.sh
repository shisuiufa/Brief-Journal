#!/bin/sh
set -e

cd /var/www

PASSPORT_SOURCE_PATH="${PASSPORT_SOURCE_PATH:-/var/www/storage}"
PASSPORT_RUNTIME_PATH="${PASSPORT_KEY_PATH:-/tmp/passport-keys}"
PASSPORT_CLIENT_NAME="${PASSPORT_CLIENT_NAME:-Brief Journal Password Client}"
PASSPORT_CLIENT_PROVIDER="${PASSPORT_CLIENT_PROVIDER:-users}"

mkdir -p "$PASSPORT_SOURCE_PATH" "$PASSPORT_RUNTIME_PATH"

if [ -f artisan ] && [ -f vendor/autoload.php ] && [ -f .env ]; then
    php artisan optimize:clear || true
    php artisan storage:link --force || true

    if [ ! -f "$PASSPORT_SOURCE_PATH/oauth-private.key" ] || [ ! -f "$PASSPORT_SOURCE_PATH/oauth-public.key" ]; then
        PASSPORT_KEY_PATH="$PASSPORT_SOURCE_PATH" php artisan passport:keys --force
    fi

    if ! grep -q '^PASSPORT_PASSWORD_CLIENT_ID=.\+' .env || ! grep -q '^PASSPORT_PASSWORD_SECRET=.\+' .env; then
        client_output="$(php artisan passport:client --password --name="$PASSPORT_CLIENT_NAME" --provider="$PASSPORT_CLIENT_PROVIDER" --no-interaction --no-ansi)"
        client_id="$(printf '%s\n' "$client_output" | sed -n 's/.*Client ID[[:space:]]*//p' | tail -n 1)"
        client_secret="$(printf '%s\n' "$client_output" | sed -n 's/.*Client Secret[[:space:]]*//p' | tail -n 1)"

        if [ -z "$client_id" ] || [ -z "$client_secret" ]; then
            echo "$client_output"
            echo "Unable to read Passport password client credentials from artisan output."
            exit 1
        fi

        if grep -q '^PASSPORT_PASSWORD_CLIENT_ID=' .env; then
            sed -i "s|^PASSPORT_PASSWORD_CLIENT_ID=.*|PASSPORT_PASSWORD_CLIENT_ID=$client_id|" .env
        else
            printf '\nPASSPORT_PASSWORD_CLIENT_ID=%s\n' "$client_id" >> .env
        fi

        if grep -q '^PASSPORT_PASSWORD_SECRET=' .env; then
            sed -i "s|^PASSPORT_PASSWORD_SECRET=.*|PASSPORT_PASSWORD_SECRET=$client_secret|" .env
        else
            printf 'PASSPORT_PASSWORD_SECRET=%s\n' "$client_secret" >> .env
        fi
    fi

    php artisan optimize:clear || true
else
    echo "Skipping Laravel bootstrap: artisan, vendor/autoload.php, or .env is missing."
fi

if [ -f "$PASSPORT_SOURCE_PATH/oauth-private.key" ] && [ -f "$PASSPORT_SOURCE_PATH/oauth-public.key" ]; then
    cp "$PASSPORT_SOURCE_PATH/oauth-private.key" "$PASSPORT_RUNTIME_PATH/oauth-private.key"
    cp "$PASSPORT_SOURCE_PATH/oauth-public.key" "$PASSPORT_RUNTIME_PATH/oauth-public.key"
    chown www-data:www-data "$PASSPORT_RUNTIME_PATH/oauth-private.key" "$PASSPORT_RUNTIME_PATH/oauth-public.key"
    chmod 600 "$PASSPORT_RUNTIME_PATH/oauth-private.key" "$PASSPORT_RUNTIME_PATH/oauth-public.key"
fi

exec php-fpm -F
