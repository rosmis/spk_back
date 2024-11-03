<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\User\UserLoginDto;
use App\Dto\User\UserRegisterDto;
use App\Exceptions\Auth\EmailNotVerifiedException;
use App\Exceptions\Auth\OtpExpiredException;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

readonly class AuthService
{
    /**
     * @throws Exception
     */
    public function login(UserLoginDto $userLoginDto): void
    {
        /** @var User $user */
        $user = User::query()
            ->where('email', $userLoginDto->email)
            ->first();

        if (! $user) {
            throw new Exception('User not found, please register first');
        }

        if (! $user->email_verified_at) {
            if ($user->email_verification_code_expiry < Carbon::now()) {
                throw new OtpExpiredException;
            } else {
                throw new EmailNotVerifiedException;
            }
        }

        if (! Auth::attempt(UserLoginDto::toArray($userLoginDto))) {
            throw new Exception('Invalid login details');
        }
    }

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

    public function sendOtp(string $email, int $otp)
    {
        return 'TODO OTP MAIL"';
    }
}
