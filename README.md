# Laravel Backend Boilerplate

## What is it about ?

this is an API First Backend developed using laravel mysql and sacntum for authentification </br>
A front end agnostic ready to use boilerplate for your next Mobile app or SPA

## Features 

- [ ] full authentication system : 
    - [x] login, logout, register
    - [ ] Email verification
    - [ ] Social login
- [x] profile management : 
    - [x] fetch and update profile data
    - [x] delete profile
- [ ] password management : 
    - [x] change password
    - [ ] reset password

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
then run

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

