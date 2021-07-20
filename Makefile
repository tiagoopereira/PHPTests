up:
	docker-compose up -d
down:
	docker-compose down
test:
	docker exec -it php php vendor/bin/phpunit
composer:
	composer install
run: up composer test