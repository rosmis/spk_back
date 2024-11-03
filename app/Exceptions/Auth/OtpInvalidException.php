<?php

namespace App\Exceptions\Auth;

use LogicException;

class OtpInvalidException extends LogicException
{
    public function __construct($message = 'Invalid OTP. Please enter the correct OTP.')
    {
        parent::__construct($message);
    }
}
