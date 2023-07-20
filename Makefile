#.PHONY: first-start
first-build: build composer-install

#.PHONY: hard-rebuild
hard-rebuild: composer-remove destroy first-build

#.PHONY: build
build:
	docker compose up --build -d

#.PHONY: destroy
destroy:
	docker compose down --rmi all --volumes --remove-orphans

#.PHONY: up
start:
	docker compose up -d

#.PHONY: down
stop:
	docker compose down

#.PHONY: restart
restart: stop start

#.PHONY: rebuild
rebuild: destroy build

#.PHONY: install
composer-install:
	docker compose exec php composer install

composer-remove:
	rm -rf vendor