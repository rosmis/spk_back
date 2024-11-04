<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

class UserAlreadyExistsException extends BusinessException
{
    public function __construct($message = 'User already exists')
    {
        parent::__construct($message);
    }
}
