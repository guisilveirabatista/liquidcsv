# About The Project

The goal of this project is to build a web application which uploads a csv file to a database.

## Prerequisites

* PHP 8.0 or higher
* Composer

## Installation

1. Clone the git project to your local repository with:
```sh
    git clone https://github.com/guisilveirabatista/liquidcsv
```
2. In the root folder of the project, run the command:
```sh
    composer install
```

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
* The `src/controller` package contains classes that handle HTTP requests.
* The `src/model` package contains the entity classes.
* The `src/gateway` package contains the database interfaces that provide CRUD functionality to the api.
* The `src/config` package contains the database configuration.

## Contact

Guilherme Silveira - guisilveirabatista@gmail.com

Project Link: [https://github.com/guisilveirabatista/liquidcsv]
