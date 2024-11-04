<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

class EmailNotVerifiedException extends BusinessException
{
    public function __construct($message = 'Email is not verified. Please enter the OTP sent to your email: ')
    {
        parent::__construct($message);
    }
}
