# Loveland Public Library Repository
This is a webapp written in PHP 7 with the Laravel 5.4 framework.  It's designed
to be a tool for the Loveland Public Library staff members to manage information
regarding all aspects of creating a safe and efficient place for every patron.

## Contributing
The application is Dockerized so all you need to do is follow the instructions
below to start contributing.

## Running in Docker
- Install Docker: [https://www.docker.com/get-started](https://www.docker.com/get-started)
- Open a Terminal or Command Line
	- clone the repository and `cd` into the project directory
	- Start Docker Containers:
		- `docker-compose up -d`
		   *Note: the first time you start the containers will take a few
		   minutes.  After that they will start much faster.*
	- Configure Laravel:
		- `docker exec hub-php php artisan storage:link`
		- `docker exec hub-php php artisan key:generate`
	- Create the database:
		- `docker exec hub-php php artisan migrate:refresh`
	- Seed the database (optional)
		- `docker exec hub-php php artisan db:seed`
		
- The application is available at http://localhost, and phpMyAdmin is at
http://localhost:8888. If accessing the containers isn't working with localhost,
try using the IP output by `docker-machine ip` or `docker inspect repository`.
