<?php

namespace App\Exceptions\Auth;

use LogicException;

class EmailNotVerifiedException extends LogicException
{
    public function __construct($message = 'Email is not verified. Please enter the OTP sent to your email: ')
    {
        parent::__construct($message);
    }
}
