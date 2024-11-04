<?php

declare(strict_types=1);

namespace App\Dto\User;

final class UserLoginDto
{
    public function __construct(
        public string $email,
        public string $password
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password']
        );
    }

    public static function toArray(UserLoginDto $userLoginDto): array
    {
        return [
            'email' => $userLoginDto->email,
            'password' => $userLoginDto->password
        ];
    }
}
