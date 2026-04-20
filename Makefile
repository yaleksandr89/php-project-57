SHELL := bash

.PHONY: init \
	build up down restart logs ps bash \
	composer-install composer-update composer-dump-autoload composer-validate \
	artisan migrate migrate-fresh seed \
	cache-clear cache-flush cache-warm reset \
	test lint lint-fix \
	hooks-init

DOCKER_ENV_FILE := .env
DOCKER_ENV_EXAMPLE := .env.example
DOCKER_COMPOSE := docker compose --env-file $(DOCKER_ENV_FILE)
APP_SERVICE := php

init:
	if [ ! -f "$(DOCKER_ENV_FILE)" ]; then cp $(DOCKER_ENV_EXAMPLE) $(DOCKER_ENV_FILE); fi

build: init
	$(DOCKER_COMPOSE) build

up: init
	$(DOCKER_COMPOSE) up -d --build

down: init
	$(DOCKER_COMPOSE) down --remove-orphans

restart: down up

logs: init
	$(DOCKER_COMPOSE) logs -f

ps: init
	$(DOCKER_COMPOSE) ps

bash: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) bash

composer-install: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) composer install

composer-update: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) composer update

composer-dump-autoload: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) composer dump-autoload -o

composer-validate:
	composer validate

artisan: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan

migrate: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan migrate

migrate-fresh: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan migrate:fresh --seed

seed: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan db:seed

cache-clear: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan config:clear
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan route:clear
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan view:clear
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan event:clear

cache-flush: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan cache:clear

cache-warm: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan config:cache
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan route:cache
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan view:cache

reset: composer-install composer-dump-autoload cache-clear

test: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) php artisan test

lint: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) ./vendor/bin/pint --test

lint-fix: init
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) ./vendor/bin/pint

hooks-init:
	git config core.hooksPath .githooks
	chmod +x .githooks/pre-commit
