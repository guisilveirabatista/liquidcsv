<?php

namespace Src\Gateway;

interface Gateway {


    public function insert(array $books);
    public function delete(int $bookId);
    public function read();
}