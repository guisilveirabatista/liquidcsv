<?php

namespace Src\Gateway;

class FileGateway
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insert(array $books)
    {
        $statement = "INSERT INTO testtable (thing_name, thing_title) VALUES (:thing_name, :thing_title);";

        try {
            $statement = $this->db->prepare($statement);

            foreach ($books as $book) {
                $statement->execute(array(
                    // 'thing_id' => $item['id'],
                    'thing_name'  => $book->author,
                    'thing_title' => $book->title ?? null,
                ));
            }

            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function read()
    {
        $statement = "SELECT * FROM testtable;";
        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
