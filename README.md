# How It Work

<img src="https://files.fm/thumb_show.php?i=bqvz2fm4a">

# Services

- laravel app
- Mariadb
- ofelia
- Mailhog
- Beanstalkd

## Folder Structure

- src contain laravel app
- mariadb persist folder
- nginx hold nginx configuration

## Laravel App

responsible for business layer logic , authorization , authentication

## Mariadb

responsible for store data persist folder `mariadb`

## ofelia

job scheduler for run laravel queue

## Beanstalkd

responsible for run queue

## Install

- clone repo `git clone https://github.com/zizohassan/jumia_task.git`
- then change your directory `cd jumia_task`
- run project `docker-compose up -d`


## Migrate Seed Sample Data

execute this command to migrate with alias `docker exec jumia_app php artisan migrate --seed`

### Run Tests

execute this command to migrate with alias `docker exec jumia_app php vendor/bin/phpunit`

## Mailhog

just for email testing tool local to make sure that emails part works fine you can check email from this
link `http://127.0.0.1:8025`

## Api Collection

api collection will found on `src/collection` folder

## Kubernetes files

- service file that will hold the service type and configuration , namespace
- ingress file for make this service public accessible , domain name , tls
- deployment file for deploy the service , when new image come deploy this service again , replicas count 
