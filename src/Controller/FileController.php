<?php

namespace Src\Controller;

use Src\Gateway\FileGateway;
use Src\Model\Book;
use Src\Exception\ApiException;

class FileController
{

    private $db;
    private $requestMethod;
    private $fileGateway;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->fileGateway = new FileGateway($db);
    }

    public function processRequest()
    {
        try {
            $this->validateRequest();
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
                    $response =  $this->delete();
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
        $fileName = 'php://input';
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

    public function delete()
    {
        //TODO: implement delete
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

    private function serverErrorResponse($errorMessage)
    {
        http_response_code(500);
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

    private function validateRequest()
    {
        if (!isset($_SERVER['CONTENT_TYPE'])) {
            throw new ApiException("You must insert a file");
        }
        if ($_SERVER['CONTENT_TYPE'] != "text/csv") {
            throw new ApiException("File must be on csv format");
        }
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
