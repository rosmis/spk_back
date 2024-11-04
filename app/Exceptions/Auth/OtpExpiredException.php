<?php

namespace App\Exceptions\Auth;

use LogicException;

class OtpExpiredException extends LogicException
{
    public function __construct($message = 'OTP has expired. Please enter the new OTP sent to your email: ')
    {
        parent::__construct($message);
    }
}
