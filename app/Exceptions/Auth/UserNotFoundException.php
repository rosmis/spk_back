<?php

namespace App\Exceptions\Auth;

use Illuminate\Validation\ValidationException;

class UserNotFoundException extends ValidationException
{
    public function __construct($message = 'User not found, please register first')
    {
        parent::__construct($message);
    }
}
