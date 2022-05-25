<?php

use Src\Controller\FileController;

require "../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
$resource = $uri[1];

// this endpoint start with /api
if ($resource !== 'api') {
    http_response_code(404);
    exit;
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new FileController($dbConnection, $requestMethod);
$controller->processRequest();
