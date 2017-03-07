call composer install
call php app/console cache:clear
call php app/console assets:install
call php app/console assetic:dump
call php app/console doctrine:database:create
call php app/console doctrine:migrations:migrate
call phpunit -c app/
start "" http://localhost:8000
call php app/console server:run
pause
