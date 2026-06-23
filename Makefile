.PHONY: help install dev test lint build start stop clean

help:
	@echo "Wct.by — Makefile"
	@echo ""
	@echo "dev     Запуск dev-окружения (serve + vite + queue)"
	@echo "test    Запуск тестов"
	@echo "lint    Проверка стиля кода"
	@echo "fix     Автоисправление стиля"
	@echo "build   Сборка frontend"
	@echo "start   Docker: запуск контейнеров"
	@echo "stop    Docker: остановка контейнеров"
	@echo "clean   Очистка кэша"

install:
	composer install
	npm install
	cp -n .env.example .env || true
	php artisan key:generate

dev:
	composer dev

test:
	php artisan test

lint:
	vendor/bin/pint --test

fix:
	vendor/bin/pint

build:
	npm run build

db-reset:
	php artisan migrate:fresh --seed

start:
	docker compose up -d

stop:
	docker compose down

clean:
	php artisan optimize:clear
