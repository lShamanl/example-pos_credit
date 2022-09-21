up: docker-up
first-init: docker-down-clear docker-pull docker-build docker-up first-init-app
init: docker-down-clear docker-pull docker-build docker-up init-app
before-deploy: php-lint php-cs php-stan # test
init-app: env-init composer-install database-create-test migrations-up fixtures
first-init-app: env-init composer-install database-create-test # make-migration migrations-up fixtures
recreate-database: database-drop database-create database-create-test

cache-clear:
	docker-compose run --rm app-php-cli php bin/console cache:clear
	docker-compose run --rm app-php-cli php bin/console cache:warmup

env-init:
	docker-compose run --rm app-php-cli rm -f .env.local
	docker-compose run --rm app-php-cli rm -f .env.test.local
	docker-compose run --rm app-php-cli ln -sr .env.local.example .env.local
	docker-compose run --rm app-php-cli ln -sr .env.test.local.example .env.test.local

fixtures:
	docker-compose run --rm app-php-cli php bin/console doctrine:fixtures:load --no-interaction
	docker-compose run --rm app-php-cli php bin/console doctrine:fixtures:load --no-interaction --env=test

make-migration:
	docker-compose run --rm app-php-cli php bin/console make:migration

migrations-up:
	docker-compose run --rm app-php-cli php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose run --rm app-php-cli php bin/console doctrine:migrations:migrate --no-interaction --env=test

migrations-down:
	docker-compose run --rm app-php-cli php bin/console doctrine:migrations:migrate prev --no-interaction

database-create:
	docker-compose run --rm app-php-cli php bin/console doctrine:database:create --no-interaction

database-create-test:
	docker-compose run --rm app-php-cli php bin/console doctrine:database:create --no-interaction --env=test

database-drop:
	docker-compose run --rm app-php-cli php bin/console doctrine:database:drop --force --no-interaction
	docker-compose run --rm app-php-cli php bin/console doctrine:database:drop --force --no-interaction --env=test

test:
	docker-compose run --rm app-php-cli ./vendor/bin/phpunit

test-coverage:
	docker-compose run --rm app-php-cli ./vendor/bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

test-unit-coverage:
	docker-compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html   var/coverage

test-integration-coverage:
	docker-compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=integration --coverage-clover var/clover.xml --coverage-html   var/coverage

test-functional-coverage:
	docker-compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=functional --coverage-clover var/clover.xml --coverage-html   var/coverage

test-unit:
	docker-compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=unit

test-functional:
	docker-compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=functional

test-integration:
	docker-compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=integration

test-acceptance:
	docker-compose run --rm app-php-cli ./vendor/bin/phpunit --testsuite=acceptance

php-stan:
	docker-compose run --rm app-php-cli ./vendor/bin/phpstan --memory-limit=-1

php-lint:
	docker-compose run --rm app-php-cli ./vendor/bin/phplint

php-cs:
	docker-compose run --rm app-php-cli ./vendor/bin/phpcbf
	docker-compose run --rm app-php-cli ./vendor/bin/phpcs

composer-install:
	docker-compose run --rm app-php-cli composer install

composer-dump:
	docker-compose run --rm app-php-cli composer dump-autoload

composer-update:
	docker-compose run --rm app-php-cli composer update

composer-outdated:
	docker-compose run --rm app-php-cli composer outdated

composer-dry-run:
	docker-compose run --rm app-php-cli composer update --dry-run

docker-up:
	docker-compose up -d

docker-rebuild:
	docker-compose down -v --remove-orphans
	docker-compose up -d --build

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build
