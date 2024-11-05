<?php

declare(strict_types=1);

namespace App\Dto\User;

final class ResetPasswordUserDto
{
    public function __construct(
        public string $email,
        public string $password,
        public int $password_reset_code,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: bcrypt($data['password']),
            password_reset_code: (int) $data['otp'],
        );
    }

    public static function toArray(ResetPasswordUserDto $userLoginDto): array
    {
        return [
            'email' => $userLoginDto->email,
            'password' => $userLoginDto->password,
        ];
    }
}
