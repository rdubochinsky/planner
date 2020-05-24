# Planner Api Application

Planner api provides the ability to create, complete and delete tasks.

## Installation

Please recheck that docker and docker-compose are installed. 

- `$ cp .env.dist .env` - *add a local config and edit it*
- `$ docker-compose up --build` - *build images and run containers*
- `$ docker-compose exec -u $UID php-fpm composer install` - *install dependencies*
- `$ docker-compose exec -u $UID php-fpm bin/console cache:warmup` - *warm-up cache*
- `$ docker-compose exec -u $UID php-fpm bin/console doctrine:migrations:migrate` - *execute database migrations*

## Tests

- `$ docker-compose exec -u $UID php-fpm bin/phpunit tests/` - *run unit tests*

## Swagger

Please open the following url http://localhost/api/doc.json to see api endpoints

## PHP CodeSniffer

- `$ docker-compose exec -u $UID php-fpm vendor/bin/php-cs-fixer fix` - *run php-cs-fixer*
