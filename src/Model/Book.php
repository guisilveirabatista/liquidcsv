<?php

namespace Src\Model;

class Book
{
    public function __construct(
        public int $id,
        public string $author,
        public string $title
    ) {
    }
}
