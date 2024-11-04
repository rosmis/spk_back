<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

class UserNotFoundException extends BusinessException
{
    public function __construct($message = 'User not found, please register first')
    {
        parent::__construct($message);
    }
}
