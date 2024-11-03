<?php

declare(strict_types=1);

namespace App\Dto\User;

use Illuminate\Support\Carbon;

final class UserRegisterDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public int $email_verification_code,
        public Carbon $email_verification_code_expiry
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: bcrypt($data['password']),
            email_verification_code: rand(100000, 999999),
            email_verification_code_expiry: Carbon::now()->addMinutes(5)
        );
    }
}
