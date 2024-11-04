<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

class EmailAlreadyVerifiedException extends BusinessException
{
    public function __construct($message = 'Email already verified')
    {
        parent::__construct($message);
    }
}
