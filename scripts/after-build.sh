#!/bin/sh

RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

cd ..
#run composer install
docker exec -it packt-admin.app composer install

echo "${GREEN}Running ${RED}CHOWN FOR PROJECT ${GREEN}:${NC}"
docker exec -it packt-admin.app chown -Rf www-data:www-data /var/www
docker exec -it packt-admin.app chown -Rf www-data:www-data /var/www/storage/

echo "${GREEN}Running ${RED}CHMOD FOR PROJECT ${GREEN}:${NC}"
docker exec -it packt-admin.app chmod -Rf 755 /var/www/storage
docker exec -it packt-admin.app chmod -Rf 755 /var/www

echo "${GREEN}Running ${RED}KEY AND CACHE REGENERATION ${GREEN}:${NC}"
docker exec -it packt-admin.app php artisan key:generate
docker exec -it packt-admin.app php artisan config:cache
docker exec -it packt-admin.app php artisan view:cache
