# Slim Framework 3 Skeleton for API

run php server 

    php -S 0.0.0.0:8080 public public/index.php 

## Install doctrine

    composer require doctrine/orm 
    ./vendor/bin/doctrine orm:schema:update --force

if our application doesn't run on Docker we can run cmd like this 
 
    ./vendor/bin/doctrine orm:schema:create --dump-sql 
    
but unfortunately we use Docker then we should run it on our Docker's container 
 
    # list all available command 
    $ docker exec -it php-fpm-service php /app/vendor/bin/doctrine
     
    # create table structure from entities we're already created 
    $ docker exec -it php-fpm-service php /app/vendor/bin/doctrine orm:schema:create
    $ docker exec -it php-fpm-service php /app/vendor/bin/doctrine orm:schema:create --dump-sql
    
    # update table structure from entities we're already updated 
    # update table structure from entities we're already updated 
    $ docker exec -it php-fpm-service php /app/vendor/bin/doctrine orm:schema-tool:update
    $ docker exec -it php-fpm-service php /app/vendor/bin/doctrine orm:schema-tool:update --force
    $ docker exec -it php-fpm-service php /app/vendor/bin/doctrine orm:schema-tool:update --dump-sql
    
