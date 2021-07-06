.PHONY: setup test up dev help

help :
	@echo "run 'make up' for 'quick starting'"
	@echo "run 'make dev' for development environment"
	@echo "run 'make test' to run unit tests"

dev :
	docker-compose -f docker-compose.yml -f docker-compose-dev.yml up -d --build

up :
	docker-compose up -d

setup :
	cd src && \
	composer install && \
	composer dump-autoload

docker-build :
	docker build -f Dockerfile-fpm -t mattftw/aligent-project .

test : setup docker-build
	docker run -it --rm --entrypoint /opt/aligent-project/vendor/bin/phpunit mattftw/aligent-project tests
