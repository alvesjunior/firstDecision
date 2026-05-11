#!/bin/sh
set -e

cd /var/www/html

# Roda como dono do bind mount (resolve permissões no macOS sem precisar
# ajustar UID/GID no .env).
HOST_UID=$(stat -c '%u' /var/www/html 2>/dev/null || stat -f '%u' /var/www/html)
HOST_GID=$(stat -c '%g' /var/www/html 2>/dev/null || stat -f '%g' /var/www/html)
[ "$HOST_UID" = "0" ] && HOST_UID=1000
[ "$HOST_GID" = "0" ] && HOST_GID=1000

# Volume nomeado do vendor começa root-owned; precisa ser gravável pelo host user.
chown -R "$HOST_UID:$HOST_GID" /var/www/html/vendor 2>/dev/null || true

# Workers do PHP-FPM precisam rodar como o usuário do host para gravar
# storage/, cache/ e logs sem erro de permissão.
cat > /usr/local/etc/php-fpm.d/zz-runtime.conf <<EOF
[www]
user = $HOST_UID
group = $HOST_GID
listen.owner = $HOST_UID
listen.group = $HOST_GID
EOF

as_app() {
    su-exec "$HOST_UID:$HOST_GID" "$@"
}

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
    chown "$HOST_UID:$HOST_GID" .env
    echo "==> .env criado a partir de .env.example"
fi

if [ ! -f vendor/autoload.php ]; then
    echo "==> composer install"
    as_app composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ -f .env ] && ! grep -qE '^APP_KEY=base64:.+' .env; then
    echo "==> php artisan key:generate"
    as_app php artisan key:generate --force
fi

echo "==> Aguardando MySQL..."
for _ in $(seq 1 30); do
    if as_app php -r "new PDO('mysql:host='.(getenv('DB_HOST')?:'db').';port='.(getenv('DB_PORT')?:'3306'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; then
        break
    fi
    sleep 2
done

if as_app php artisan migrate:status >/dev/null 2>&1; then
    echo "==> migrate (pendentes)"
    as_app php artisan migrate --force
else
    echo "==> migrate --seed (banco vazio)"
    as_app php artisan migrate --force --seed
fi

# php-fpm precisa subir como root (master) para abrir log/socket;
# os workers herdam HOST_UID/GID via o zz-runtime.conf acima.
# Outros comandos (composer, artisan, sh) caem para o usuário do host.
if [ "$1" = "php-fpm" ]; then
    exec "$@"
else
    exec su-exec "$HOST_UID:$HOST_GID" "$@"
fi
