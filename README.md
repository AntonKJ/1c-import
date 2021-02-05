# SIONIK-1c-import

http://test.sionic.ru/test.html

Выполнить docker-compose up -d из корневой папки проекта

Переименовать /src/sionik/env в .env

#Установка db_sionik из корневой папки проекта (testsionik название папки в которую сделаешь git clone)

```
cat db_sionik.sql | docker exec -i testsionik_mariadb_1 /usr/bin/mysql -u root --password=rootpwd6421 sionik
```

#==================#IMPORT#==================#

docker exec -it testsionik_app_1 su

cd /home/sionik/import

bash import.sh 

> Data import completed successfully!

#==================#END#==================#


http://localhost/adminpanel

login: webmaster
pass: webmaster

# If not DB errors

Добавить в docker-compose.yaml в конце 
```
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    links: 
      - mariadb:db
    ports:
      - 8765:80
    environment:
      MYSQL_ROOT_PASSWORD: rootpwd6421
      UPLOAD_LIMIT: 300000000
    depends_on:
      - mariadb
```
http://localhost:8765

Phpmyadmin 
login: root
pass: rootpwd6421

# Запускать без докера, нужно взять проект из папки src/sionik (все файлы)

в файле .env настройки БД 
```
DB_DSN=mysql:host=mariadb;port=3306;dbname=sionik
DB_USERNAME=root
DB_PASSWORD=rootpwd6421
DB_TABLE_PREFIX=
DB_CHARSET=utf8mb4
```

для импорта в файле import/DB.php
```
  private const DB_HOST = 'mariadb';
	private const DB_NAME = 'sionik';
	private const DB_USER = 'root';
	private const DB_PASS = 'rootpwd6421';
```
менять на свои

# if not PDO Driver
```
docker exec -it testsionik_php_1 su
docker-php-ext-install pdo_mysql
#or
docker-compose exec testsionik_php_1 docker-php-ext-install pdo_mysql
docker-compose exec testsionik_php_1 docker-php-ext-install intl
```
#docker-compose reload 

# for add BD не обязательно если установилась  БД 
```
docker exec -it testsionik_app_1 su
cd /home/sionik
php console/yii help

php console/yii migrate

php console/yii rbac-migrate
```
# if exception Message format 'date' is not supported. You have to install PHP intl extension to use this feature.
```
docker exec -it testsionik_php_1 su
apt-get -y update \
    && apt-get install -y libicu-dev\
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl
```
#or 
```
docker-php-ext-install intl
```

# if gd not available 
```
docker exec -it testsionik_php_1 su

apt-get update && \
    apt-get install -y \
        zlib1g-dev libpng-dev\
    && docker-php-ext-install gd
```

#Module 'sodium' already loaded

# RELOAD DOCKER-COMPOSE stop/up PAGE LOCALHOST/ ADMINPANEL 

