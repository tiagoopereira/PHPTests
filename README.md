# Testes com PHP
## Execução
- Utilizando make (Makefile):
  - make run

- composer install (Para instalar as dependências e gerar o arquivo de autoload).
- Utilizando docker:
  - docker-compose up -d
  - docker exec -it php php vendor/bin/phpunit
- Sem docker:
  - *Necessário PHP 8+* 
  - php -S 0.0.0.0:80 -t public/
  - php vendor/bin/phpunit