## PHP Bank Platform

<h2 align="center">
    Simple Bank Account
</h2>

## 🚀 Technologies

This project was developed with the following technologies:

- [PHP 8.3](https://php.net)
- [Laravel](https://laravel.com)
- [MySQL 5.7](https://mysql.com)
- [VueJs]()
- [Xdebug](https://xdebug.org/)
- [Docker](https://docker.com)
- [Nginx](https://nginx.org/en/)



## 📑 Project

Simple Bank Account platform with Laravel, Vue JS, MySQL, and Docker.  
This project contains authentication and authorization implemented in both the backend and frontend.  
The admin user is created as soon as the application is published through the execution of migrations and seeders.  
When a user signs up, an Observer in the backend automatically creates a new bank account with a balance of $0.0

## ⚙️ Installation and running

## 1- Clone the Project
### Step by step
Clone the Repository
```sh
git clone https://github.com/thiagoluna/turno-php-vuejs-bank-system.git
```

Create the file .env
```sh
cp .env.example .env
```

Up project containers
```sh
docker-compose up -d
```

Access the container
```sh
docker-compose exec app bash
```

Install project dependencies
```sh
composer install
npm install
npm run prod
```

Create tables and add Admin user
```sh
php artisan migrate --seed
```

## 💻 Access the Application by Browser
- http://localhost:8990
- This application was published on an EC2 instance on AWS and can be accessed via the url http://18.217.24.99:8990.  
But it will only be published for a short time.

## 🚀 Laravel features used in this application
- Migration, Factory, Seeder, FormRequest, Mutator, Job, Observer, Middleware, Sanctum.

## 🚀 VueJS features used in this application
- Vue Router, Vuex, Snotify, Axios, V-Mask

## 🗃️ Database
- MySQL
- Eloquent ORM for working with a database, where tables have a corresponding "Model" that is used to
  interact with this table.

## 📔 Design Pattern
- **Repository Design Pattern** to separate data access (Repositories) from business logic (Service Layers).  
  With this standard we have a division of responsibilities, making each layer of the application as simple as possible,
  helping the application to be more easily scalable.  
- The concept of **DTO** was used to facilitate data transport by encapsulating primitive data as objects.  
- And also the concept of **Service Layer** presented by Martin Fowler, where "each layer establishes a set of
  available operations and coordinates the application's response to each operation."

**NOTE:** The principles of SOLID, Object Calisthenics and DRY were also used in this project.

## 🎯 Automated Tests
- DTOs Tests
- Enums Tests
- FormRequests Tests
- Services Tests  

There are 49 tests and 92 assertions.  
To run the tests, run one of the commands below within the project folder:
```sh
docker-compose exec app vendor/bin/phpunit
docker-compose exec app php artisan test
```

## 🛠️ Error Handling
Each type of error has a specific Exception, which when thrown is always caught in the Controller, which generates a log and
returns a personalized message for the request made.  
Logs will be recorded in the laravel.log file, following the Laravel standard, and can be sent via Slack to
a group, where error monitoring will be much more effective.

Thinking about performance, every log will be generated from the triggering of a Job that performs the action of recording it in a
asynchronous.

## 🙋‍♂️ Developed by
Thiago Luna: [Linkedin!](https://www.linkedin.com/in/thiago-luna/)

## Some Skills
![PHP](https://img.shields.io/badge/PHP-fff?style=for-the-badge&logo=php)
![LARAVEL](https://img.shields.io/badge/LARAVEL-000?style=for-the-badge&logo=laravel)
![RABITMQ](https://img.shields.io/badge/rabbitmq-E34F26?style=for-the-badge&logo=rabbitmq&logoColor=white)
![MYSQL](https://img.shields.io/badge/MySQL-fff?style=for-the-badge&logo=mysql)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![NodeJS](https://img.shields.io/badge/node-44883e?style=for-the-badge&logo=node.js&logoColor=black)
![Solidity](https://img.shields.io/badge/solidity-96C9F4?style=for-the-badge&logo=solidity&logoColor=black)
![Bootstrap](https://img.shields.io/badge/bootstrap-000?style=for-the-badge&logo=bootstrap&logoColor=553C7B)
[![Git](https://img.shields.io/badge/Git-000?style=for-the-badge&logo=git&logoColor=E94D5F)](https://git-scm.com/doc)
[![GitHub](https://img.shields.io/badge/GitHub-000?style=for-the-badge&logo=github&logoColor=30A3DC)](https://docs.github.com/)
