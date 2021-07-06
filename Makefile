help :
	@echo "run 'make up' for 'quick starting'"
	@echo "run 'make dev' for development environment"

dev :
	docker-compose -f docker-compose.yml -f docker-compose-dev.yml up -d --build

up:
	docker-compose up -d
