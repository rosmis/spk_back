<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

class InvalidLoginDetailsException extends BusinessException
{
    public function __construct($message = 'Invalid login details')
    {
        parent::__construct($message);
    }
}
