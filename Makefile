.PHONY: test swagger install migrate demigrate remigrate

test:
	@docker-compose exec php ./vendor/bin/codecept run

swagger:
	@docker-compose exec -u `id -u` php ./vendor/bin/openapi --output swagger config jimmy
	@echo "Swagger generated"

install:
	@docker-compose exec -u `id -u` php composer install --no-cache --ignore-platform-req=php

migrate:
	@docker-compose exec php ./vendor/bin/doctrine-migrations migrate
	@echo "Migrate complete"

demigrate:
	@docker-compose exec php ./vendor/bin/doctrine-migrations migrate 0
	@echo "Migrate down"

remigrate: demigrate migrate