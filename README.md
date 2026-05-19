# Lindon

PHP member site with registration, login, profile management, admin panel, and contact form.

## Requirements

- PHP 8.1+
- MySQL / MariaDB
- [Composer](https://getcomposer.org/)

## Setup

1. Clone the repository and install dependencies:

   ```bash
   composer install
   ```

2. Copy environment config and edit values:

   ```bash
   cp .env.example .env
   ```

3. Create the database and run migrations:

   ```bash
   php migrate.php
   ```

4. Point your web server document root at this directory (or serve under a subpath such as `/lindon`).

## Configuration

See `.env.example` for database, mail, captcha (Cloudflare Turnstile / reCAPTCHA), and app URL settings.
