<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BusinessException;

class OtpInvalidException extends BusinessException
{
    public function __construct($message = 'Invalid OTP. Please enter the correct OTP: ')
    {
        parent::__construct($message);
    }
}
