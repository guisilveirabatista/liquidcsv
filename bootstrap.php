<?php

require 'vendor/autoload.php';

use Src\Config\DbConnector;
use Dotenv\Dotenv;

set_error_handler("Src\Model\ErrorHandler::handleError");
set_exception_handler("Src\Model\ErrorHandler::handleException");

$dotenv = new DotEnv(__DIR__);
$dotenv->load();

$dbConnection = (new DbConnector())->getConnection();
