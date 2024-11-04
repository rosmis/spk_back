<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

class OtpExpiredException extends BusinessException
{
    public function __construct($message = 'OTP has expired. Please enter the new OTP sent to your email: ')
    {
        parent::__construct($message);
    }
}
