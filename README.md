# Laravel Backend Boilerplate

## What is it about ?

this is an API First Backend developed using laravel mysql and sacntum for authentification </br>
A front end agnostic ready to use boilerplate for your next Mobile app or SPA

## Features and Todo's

- [x] authentication system
- [x] profile fetch and update data
- [x] reset passwords
- [x] email verification
- [ ] social login
- [ ] user roles
- [ ] user crud for admins
- [ ] public upload example (profile picture)
- [ ] local upload example : private attachments

you can add suggestions by modifying this file :) 

## Installation 

#### Docker ?

if you have docker installed , all you have to do is running 

```
$ git clone https://github.com/hijenhek/laravel-backend-boilerplate 
$ cd laravel-backend-boilerplate 
$ composer install
$ ./vendor/bin/sail/up

```

and the app will be accessible by default via `localhost` as programmed in `docker-compose.yml`

#### locally ?

you need to have 

```
composer
php ^8.0
mysql
```
then run these commands

```
$ git clone https://github.com/hijenhek/laravel-backend-boilerplate 
$ cd laravel-backend-boilerplate 
$ composer install
$ cp .env.example .env // set up database credentials ...
$ php artisan key:generate
$ php artisan migrate --seed
$ php artisan serve
```

and the app will be accessible by default via `localhost:8000` 

