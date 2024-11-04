<?php

namespace App\Exceptions\Auth;

use Illuminate\Validation\ValidationException;

class OtpExpiredException extends ValidationException
{
    public function __construct($message = 'OTP has expired. Please enter the new OTP sent to your email: ')
    {
        parent::__construct($message);
    }
}
