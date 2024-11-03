<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\User\UserRegisterDto;
use App\Models\User;
use Exception;

readonly class AuthService
{
    /**
     * @throws Exception
     */
    public function register(
        UserRegisterDto $userRegisterDto,
        ?User $user
    ): User {
        if ($user) {
            throw new Exception('User already exists');
        }

        /** @var User $user */
        $user = User::query()
            ->create([
                'name' => $userRegisterDto->name,
                'email' => $userRegisterDto->email,
                'password' => $userRegisterDto->password,
                'email_verification_code' => $userRegisterDto->email_verification_code,
                'email_verification_code_expiry' => $userRegisterDto->email_verification_code_expiry,
            ]);

        $this->sendOtp($user->email, $userRegisterDto->email_verification_code);

        return $user;
    }

        public function sendOtp(string $email, int $otp): void
        {

        }
}
