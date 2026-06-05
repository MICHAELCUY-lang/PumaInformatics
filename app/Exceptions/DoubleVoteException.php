<?php

namespace App\Exceptions;

use Exception;

class DoubleVoteException extends Exception
{
    public function __construct($message = "You have already cast a vote in this session.", $code = 403, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
