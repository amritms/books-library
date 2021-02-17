# Backend Dev Challenge - Book Library

## Assumptions
1. Restful API for all endpoints.
1. No matter who returns the book, it will be logged in to original lender in user_action_logs table.

## Libraries/Tools used
* Laravel 8
* Laravel Sanctum for authentication system.
* Postgres

## How to setup
Run the following commands to setup.
***If you want to use local php server,  given php 7.3+, composer postgres is available***
1. git clone git@github.com/amritms/books-library.git
1. cd book-library
1. cp .env.example .env
1. php artisan key:generate
1. update .env to change the database name and credentials
1. composer install
1. php artisan migrate --seed
1. php artisan test -> to run the test
1. php artisan serve
1. you can access the api at http://localhost:8000/api
1. visit [API documentation](docs/API.md)

***If you want to use docker instead of local php server, given you have docker available***
1. git clone git@github.com/amritms/book-library.git
1. cd book-library
1. cp .env.example.docker .env
1. update .env to change the database name. *The docker will create database by reading in the .env file.* 
1. docker-compose run --rm library_composer install
1. docker-compose run --rm library_artisan migrate --seed
1. docker-compose run --rm library_artisan test -> to run the test
1. you can access the api at http://localhost/api
1. visit [API documentation](docs/API.md)

## API Documentation
[API Documentation](docs/API.md)

## If it was a bigger real project
If it was a bigger real project, doing the following would be better:
1. Use Laravel fortify because it provides login, registration, password reset, email verification etc out of the box.
1. Use Laravel sanctum SPA authentication instead of token based authentication because SAP authentication uses Laravel's built-in cookie based session authentication services which provides CSRF protection and session authentication.
1. Use Repository Pattern while writing code to adhere SOLID principles.
1. Write more tests.
1. Implement Route 53 for DNS, AWS Lambda, RDS database with Multiple Availability Zones failover and Read replicas, S3 for storage, Elasticache for caching, SQS for queues, cloudwatch for logs as server infrastructure. 
