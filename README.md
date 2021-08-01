<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to run CampApp

1. First download the repository.
2. Secondly, download the libraries needed for the project. To do this, in the project directory, type:
```
composer install
```
and
```
npm install
```
3. The project will use the MySQL database, so you can use Xampp with MySQL or use the attached docker-compose.yml file and run app with the docker.
4. If you chose a docker, configure Laravel Sail ([Laravel Sail Doc](https://laravel.com/docs/8.x/sail)) and run docker containers.
5. Finally, migrate the database with the command:
```
php artisan migrate
```
6. And test the app as much as you want!
