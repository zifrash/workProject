.DEFAULT_GOAL:=help

.PHONY: first-start
first-build: build composer-install ## Команда первого запуска, запустит наши образы (php, nginx, postgres, redis, rabbitmq), и произведет первичную настройку. Так же запустит composer install.

.PHONY: hard-rebuild
hard-rebuild: composer-remove destroy first-build ## Удалит папку vendor (composer), а так же все докер образы. И заново все соберет (first-build).

.PHONY: build
build: ## Скачает, докер образы и запустит их.
	docker compose up --build -d

.PHONY: destroy
destroy: ## Удалит докер образы.
	docker compose down --rmi all --volumes --remove-orphans

.PHONY: start
start: ## Запустит работу докер образов.
	docker compose up -d

.PHONY: stop
stop: ## Остановит работу докер образов.
	docker compose down

.PHONY: restart
restart: stop start ## Перезапустит докер образы.

.PHONY: rebuild
rebuild: destroy build ## Переустановит докер образы (destroy build).

.PHONY: composer-install
composer-install: ## Установит пакеты composer (composer.json).
	docker compose exec php composer install

.PHONY: composer-remove
composer-remove: ## Удалит пакеты composer (папку vendor).
	rm -rf vendor

.PHONY: help
help:
	@awk 'BEGIN {FS = ":.*##";} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
