#.PHONY: build
build:
	sudo docker-compose up --build -d

#.PHONY: destroy
destroy:
	sudo docker-compose down --rmi all --volumes --remove-orphans

#.PHONY: up
up:
	sudo docker-compose up -d

#.PHONY: down
down:
	sudo docker-compose down

#.PHONY: restart
restart: down up

#.PHONY: rebuild
rebuild: destroy build
