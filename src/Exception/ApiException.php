<?php

namespace Src\Exception;

class ApiException extends \Exception
{
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}
