#.PHONY: build
build:
	docker compose up --build -d

#.PHONY: destroy
destroy:
	docker compose down --rmi all --volumes --remove-orphans

#.PHONY: up
up:
	docker compose up -d

#.PHONY: down
down:
	docker compose down

#.PHONY: restart
restart: down up

#.PHONY: rebuild
rebuild: destroy build

#.PHONY: install
install:
	docker compose exec php composer install