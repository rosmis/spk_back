<?php

namespace App\Exceptions\Auth;

use Illuminate\Validation\ValidationException;

class EmailNotVerifiedException extends ValidationException
{
    public function __construct($message = 'Email is not verified. Please enter the OTP sent to your email: ')
    {
        parent::__construct($message);
    }
}
