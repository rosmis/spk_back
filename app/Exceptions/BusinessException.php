<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

abstract class BusinessException extends Exception
{
    protected $code = 422; // Default HTTP status code
}