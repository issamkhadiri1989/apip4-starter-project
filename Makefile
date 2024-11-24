SYMFONY_VERSION=7.1.*

start:
	docker-compose up -d --no-recreate --remove-orphans

stop:
	docker container stop $$(docker container ps -qa)

build:
	docker-compose build --force-rm

enter:
	docker-compose exec app bash

ps:
	docker-compose ps

init: stop start
	docker-compose exec app composer self-update
	docker-compose exec app composer create-project symfony/skeleton:$(SYMFONY_VERSION) .
	docker-compose exec app composer require webapp

install: install-packages install-database

install-packages:
	docker-compose exec app composer install

update-schema: 
	docker-compose exec app php bin/console doctrine:schema:update --force

load-fixtures:
	docker-compose exec app php bin/console doctrine:fixtures:load

install-database: update-schema load-fixtures

start-api:
	docker compose up -d --build

install-api:
	docker-compose exec app composer create-project symfony/skeleton:$(SYMFONY_VERSION) .
	docker compose exec app composer req api

reload-nginx:
	docker-compose exec  nginx nginx -s reload

restart:
	docker compose restart

generate-pairs:
	docker-compose exec app php bin/console lexik:jwt:generate-keypair
