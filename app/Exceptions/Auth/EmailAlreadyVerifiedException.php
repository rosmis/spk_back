<?php

namespace App\Exceptions\Auth;

use Illuminate\Validation\ValidationException;

class EmailAlreadyVerifiedException extends ValidationException
{
    public function __construct($message = 'Email already verified')
    {
        parent::__construct($message);
    }
}
