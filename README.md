# About The Project

The goal of this project is to build a web application which uploads the contents of a csv file to a database and display it on a table.

## Prerequisites

* PHP 8.0 or higher
* Composer
* MySql Database

## Installation

1. Clone the git project to your local repository with:
```sh
    git clone https://github.com/guisilveirabatista/liquidcsv
```
2. In the root folder of the project, run the command:
```sh
    composer install
```

3. Make sure that you have a MySql database running and configured according to the parameters on the file `.env`

## Usage

1. In the root folder of the project, run the command:
   ```sh
   php -S localhost:8080
   ```
2. Open following link on your browser:
   ```sh
   http://localhost:8080/public/client
   ```

4. Select the csv file and click on `Upload`

## Architecture

This project follows the MVC and Rest architectures. The structure is organised as follows:

* The `public` folder contains the html page used as the frontend of the application.
* The `api` folder contains the end point of the rest api.
* The `migrations` folder contains sql commands to migrate the database tables.
* The `src/controller` package contains classes that handle HTTP requests.
* The `src/model` package contains the entity classes.
* The `src/gateway` package contains the database interfaces that provide CRUD functionality to the api.
* The `src/config` package contains the database configuration.

## What can be done moving forward?

There are a few improvements I still would like to make in this application, some of them being:

* Add pagination to the table
* Add edit records feature
* Implement unit tests

## Contact

Guilherme Silveira - guisilveirabatista@gmail.com

Project Link: [https://github.com/guisilveirabatista/liquidcsv]
