# SIRM

## Prerequisites

- PHP 8.5 with the MySQL and multibyte string extensions enabled (`pdo_mysql`, `mbstring`)
- Composer
- Node.js and npm
- Docker
- Symfony CLI
- Google Chrome or Chromium, required for Angular/Karma unit tests

Check the required PHP extensions with:

```bash
php -m | grep -Ei 'pdo_mysql|mbstring'
```

If either extension is missing on Ubuntu/Debian:

```bash
sudo apt install php8.5-mysql php8.5-mbstring
```

## First Setup After Clone

Start the MySQL database from the backend Compose file:

```bash
cd sirm-back
docker compose up -d database
```

Install backend dependencies and prepare the database:

```bash
composer install
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --append
```

Install frontend dependencies:

```bash
cd ../sirm-front
npm install
```

## Launch The App

Terminal 1, database:

```bash
cd sirm-back
docker compose up -d database
```

Terminal 2, backend:

```bash
cd sirm-back
symfony server:start --port=8000 --no-tls
```

Terminal 3, frontend:

```bash
cd sirm-front
npm start
```

Open:

```text
http://localhost:4200
```

Default fixture login:

```text
admin
admin123
```

## Important Configuration

The backend database URL is:

```text
mysql://root:root@127.0.0.1:3306/sirm_db?serverVersion=8.0
```

The Angular dev proxy points `/api` to:

```text
http://127.0.0.1:8000
```

So if you run the Symfony backend with TLS, update `sirm-front/proxy.conf.json` to use `https://127.0.0.1:8000`. The default documented setup uses `--no-tls`.

## Verification

Backend:

```bash
cd sirm-back
composer validate --no-check-publish
php bin/console lint:container
php bin/console doctrine:schema:validate --no-interaction
php bin/console doctrine:migrations:status --no-interaction
./vendor/bin/phpunit
```

Frontend:

```bash
cd sirm-front
npm run build
npm test -- --watch=false --browsers=ChromeHeadless
```

If `ChromeHeadless` is not found, install Google Chrome or Chromium, or set `CHROME_BIN` to the browser binary path.
