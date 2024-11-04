<?php

namespace App\Exceptions\Auth;

use Illuminate\Validation\ValidationException;

class OtpInvalidException extends ValidationException
{
    public function __construct($message = 'Invalid OTP. Please enter the correct OTP: ')
    {
        parent::__construct($message);
    }
}
