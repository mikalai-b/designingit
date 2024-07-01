Professions# Cosmeticrx.Com

* NONE: https://cosmeticrx.com/
* remote-dev: https://cosmeticrx.designingit.co/

## How to deploy

Autodeploy triggered by push:

* Branch NONE deploy to prod.
* Branch "remote-dev" deploy to dev.

## Getting Started

### Dependencies

* Mysql v5.5
* php v7.1
* CraftCMS v2.9.2

# Laracraft

Laracraft is Craft and Laravel merged together.  Craft is set up initially in accordance with Padstone, see: https://github.com/imarc/padstone

Basic concepts:

- Single entry index.php
- If page is not found in Laravel, it tries Craft
- Laravel has been modified to use a separate `.env.laravel`
- Laravel uses Twig (see: https://github.com/rcrowe/TwigBridge)
- Laravel used Doctrine (see: https://www.laraveldoctrine.org/)
- Craft has been modified to have it's templates moved to `resources` directory

## Front End

See: https://gitlab.imarc.net/imarc/laravel-mix-starter


## Engineering Notes (Dashboard)

### Regenerate Base Entities and New Repositories

`php artisan tenet:generate:classes; composer dump-autoload`

### Dumping SQL Difference

`php artisan doctrine:schema:update --sql`

### Force Update Schema

`php artisan doctrine:schema:update --force`

### Twig Facades Analogs

See the Facades array in the `twigbridge.php` config.  This should be pretty self explanatory.
