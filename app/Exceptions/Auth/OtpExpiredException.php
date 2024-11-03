<?php

namespace App\Exceptions\Auth;

use LogicException;

class OtpExpiredException extends LogicException
{
    public function __construct($message = 'OTP has expired. A new OTP has been sent to your email address.')
    {
        parent::__construct($message);
    }
}
