#!/bin/sh
set -e

cd /var/www/html

export PORT="${PORT:-3000}"

mkdir -p \
    storage/framework/cache/data \
    storage/framework/views \
    storage/framework/sessions \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan storage:link --force 2>/dev/null || true

if [ "${WAIT_FOR_DB:-true}" = "true" ] && [ "${DB_CONNECTION:-}" = "mysql" ]; then
    echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
    php -r "
        \$host = getenv('DB_HOST') ?: '127.0.0.1';
        \$port = getenv('DB_PORT') ?: '3306';
        \$user = getenv('DB_USERNAME') ?: 'root';
        \$pass = getenv('DB_PASSWORD') ?: '';
        for (\$i = 0; \$i < 30; \$i++) {
            try {
                new PDO(\"mysql:host=\$host;port=\$port\", \$user, \$pass, [
                    PDO::ATTR_TIMEOUT => 3,
                ]);
                echo \"Database is reachable.\n\";
                exit(0);
            } catch (Throwable \$e) {
                sleep(2);
            }
        }
        fwrite(STDERR, \"Database connection failed after 60 seconds.\n\");
        exit(1);
    "
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

if [ "${APP_ENV:-local}" = "production" ]; then
    php artisan optimize:clear --no-interaction
    php artisan config:cache --no-interaction
    php artisan route:cache --no-interaction
    php artisan view:cache --no-interaction
fi

envsubst '${PORT}' < /etc/nginx/sites-available/default.template > /etc/nginx/sites-available/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
