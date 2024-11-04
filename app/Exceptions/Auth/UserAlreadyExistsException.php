<?php

namespace App\Exceptions\Auth;

use Illuminate\Validation\ValidationException;

class UserAlreadyExistsException extends ValidationException
{
    public function __construct($message = 'User already exists')
    {
        parent::__construct($message);
    }
}
