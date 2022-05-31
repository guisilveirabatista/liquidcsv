<?php

namespace Src\Controller;

use Src\Gateway\FileGateway;
use Src\Model\Book;
use Src\Exception\ApiException;

//TODO: Implement pagination
//TODO: Implement unit tests
class FileController
{

    private $db;
    private $requestMethod;
    private $fileGateway;

    private $allowedMimeTypes = array(
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/x-csv',
        'text/x-csv',
        'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.msexcel',
        'text/plain'
    );

    public function __construct($requestMethod, $gateWay)
    {
        $this->requestMethod = $requestMethod;
        $this->fileGateway = $gateWay;
    }

    public function processRequest($bookId)
    {
        try {
            switch ($this->requestMethod) {
                case 'GET':
                    $response = $this->get();
                    break;
                case 'POST':
                    $response = $this->post();
                    break;
                case 'PUT':
                    $response = $this->put();
                    break;
                case 'DELETE':
                    $response =  $this->delete($bookId);
                    break;
                default:
                    $response = $this->notFoundResponse();
                    break;
            }
        } catch (ApiException $e) {
            $response = $this->badRequestResponse($e->getMessage());
        }
        $this->sendResponse($response);
    }

    public function get()
    {
        $result = $this->fileGateway->read();

        http_response_code(200);
        $response['body'] = json_encode($result);
        return $response;
    }

    public function post()
    {
        $this->validatePost();

        $fileName = $this->getFileName();

        $delimiter = ';';

        $convertedData = $this->convertCsvToBooks($fileName, $delimiter);

        $this->fileGateway->insert($convertedData);

        http_response_code(201);
        $response['body'] = null;
        return $response;
    }

    public function put()
    {
        //TODO: implement put
    }

    public function delete($bookId)
    {

        $rowsAffected = $this->fileGateway->delete($bookId);

        http_response_code(200);

        if (empty($rowsAffected) || $rowsAffected = 0) {
            http_response_code(404);
        }

        $response['body'] = null;
        return $response;
    }

    private function notFoundResponse()
    {
        http_response_code(404);
        $response['body'] = null;
        return $response;
    }

    private function badRequestResponse($errorMessage)
    {
        http_response_code(400);
        $response['body'] = $errorMessage;
        return $response;
    }

    function convertCsvToBooks($fileName, $delimiter)
    {
        if (!($fp = fopen($fileName, 'r'))) {
            throw new ApiException("Couldn't read file");
        }

        //read csv headers
        $key = fgetcsv($fp, separator: $delimiter);

        // parse csv rows into array
        $result = array();
        while (($row = fgetcsv($fp, separator: $delimiter)) !== FALSE) {
            if (count($key) != count($row)) {
                throw new ApiException("The file must have the same number of headers and columns");
            }
            $result[] = array_combine($key, $row);
        }

        // release file handle
        fclose($fp);

        $books = array();

        foreach ($result as $item) {

            // Remove any invalid or hidden characters
            $item = $this->sanitize($item);

            if (!is_numeric($item["id"])) {
                throw new ApiException("All the ids in the file have to be of type integer");
            }
            $book = new Book(
                id: $item["id"] ?? 0,
                author: $item["author"],
                title: $item["title"],
            );
            array_push($books, $book);
        }

        return $books;
    }

    private function validatePost()
    {
        $contentType = $this->getContentType();
        if (!isset($contentType)) {
            throw new ApiException("You must insert a file");
        }

        if (!in_array($contentType, $this->allowedMimeTypes)) {
            throw new ApiException("File must be on a valid csv format");
        }
    }

    private function getFileName()
    {
        $fileName = 'php://input';
        if ($this->isAjaxRequest()) {
            $fileName = $_FILES['file']['tmp_name'];
        }
        return $fileName;
    }

    private function getContentType()
    {
        $contentType = isset($_SERVER['CONTENT_TYPE']) ?? "";
        if ($this->isAjaxRequest()) {
            $contentType = mime_content_type($this->getFileName());
        }
        return $contentType;
    }

    public function isAjaxRequest()
    {
        return isset($_FILES['file']['tmp_name']);
    }

    private function sanitize($row)
    {
        return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row);
    }

    private function sendResponse($response)
    {
        if ($response['body']) {
            echo $response['body'];
        }
    }
}
