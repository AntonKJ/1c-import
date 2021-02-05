# sionik-1c-import

http://test.sionic.ru/test.html

Выполнить docker-compose up -d из корневой папки проекта

#Установка db mysql из корневой папки проекта (testsionik название папки в которую сделаешь git clone)

cat db_sionik.sql | docker exec -i testsionik_mariadb_1 /usr/bin/mysql -u root --password=rootpwd6421 sionik


#==================#IMPORT#==================#

docker exec -it testsionik_app_1 su

cd /home/sionik/import

bash import.sh 

> Data import completed successfully!

#==================#END#==================#


http://localhost/adminpanel

login: webmaster
pass: webmaster

#if not PDO Driver
docker exec -it testsionik_php_1 su
docker-php-ext-install pdo_mysql
#or
docker-compose exec testsionik_php_1 docker-php-ext-install pdo_mysql
docker-compose exec testsionik_php_1 docker-php-ext-install intl
#docker-compose reload 

# for add BD не обязательно если установилась  БД 
docker exec -it testsionik_app_1 su
 console/yii help

 console/yii migrate

 console/yii rbac-migrate

#if exception Message format 'date' is not supported. You have to install PHP intl extension to use this feature.
docker exec -it testsionik_php_1 su
apt-get -y update \
    && apt-get install -y libicu-dev\
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

#or 

docker-php-ext-install intl

# if gd not available 
docker exec -it testsionik_php_1 su

apt-get update && \
    apt-get install -y \
        zlib1g-dev libpng-dev\
    && docker-php-ext-install gd

#Module 'sodium' already loaded

RELOAD PAGE LOCALHOST/ ADMINPANEL

