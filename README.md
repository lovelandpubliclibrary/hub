# ![Loveland Public Library Logo](public/images/lpl_logo.png) Loveland Public Library Repository
This is a webapp written in PHP 7 with the Laravel 5.4 framework.  It's designed to be a tool for the staff of Loveland Public Library
to help manage information regarding all aspects of creating an safe, efficient place for Loveland residents.

# Contributing
All you need to contribute is a LAMP stack (see below) and a basic knowledge of the Laravel framework.

## Running in Docker
- Install Docker: [https://www.docker.com/community-edition#/download](https://www.docker.com/community-edition#/download)
- Open a Terminal or Command Line
	- clone the repository and `cd` into the repository root
	- Start Containers:
		- `docker-compose up -d`
		   *Note: the first time you start the containers will take a few minutes.  After that they will start much faster.*
	- Create tables in database:
		- `docker exec repository php artisan migrate`
	- Seed tables with data:
		- `docker exec repository php artisan db:seed`
- The application is being served on port 10000, and phpMyAdmin is on port 8888. MySQL is also available on port 3307 if you prefer to use a different database management tool. If accessing the containers isn't working with localhost, try using the IP output by `docker-machine ip` or `docker inspect repository`.
