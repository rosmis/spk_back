<?php

declare(strict_types=1);

namespace App\Dto\User;

final class UserOtpDto
{
    public function __construct(
        public string $email,
        public int $otp
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            otp: (int) $data['otp']
        );
    }

    public static function toArray(UserOtpDto $userLoginDto): array
    {
        return [
            'email' => $userLoginDto->email,
            'otp' => $userLoginDto->otp
        ];
    }
}
