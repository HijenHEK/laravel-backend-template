# Laravel Backend Template

#### i use this as a starting point for my backend projects , it saves time with basic auth functionalities and has code examples for repetitive blocks 

## What is it about ?

this is an API First Backend developed using laravel mysql and sacntum for authentification </br>
A front end agnostic ready to use boilerplate for your next Mobile app or SPA

## Features and Todo's

- [x] authentication system
- [x] profile fetch and update data
- [x] reset passwords
- [x] email verification
- [x] user roles
- [x] user crud for admins
- [x] public upload example (profile picture)
- [x] local upload example : private attachment
- [x] MFA
- [ ] social login

## Admin provider Todo

- [x] seperate admin provider (under feature/admin-provider)
- [x] authentication system
- [ ] admin profile
- [ ] clean main roles system

you can add suggestions by modifying this file :) 

## Installation 

### Docker ?

if you have docker installed , all you have to do is running 

```
$ git clone https://github.com/hijenhek/laravel-backend-boilerplate 
$ cd laravel-backend-boilerplate 
$ composer install
$ ./vendor/bin/sail/up

```

and the app will be accessible by default via `localhost` as programmed in `docker-compose.yml`

### locally ?

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

## Testing

```shell
$ php artisan test
```

