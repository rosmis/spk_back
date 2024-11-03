<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\User\UserLoginDto;
use App\Dto\User\UserOtpDto;
use App\Dto\User\UserRegisterDto;
use App\Exceptions\Auth\EmailNotVerifiedException;
use App\Exceptions\Auth\OtpExpiredException;
use App\Exceptions\Auth\OtpInvalidException;
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
                $this->resendOtp($user->email);
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

    /**
     * @throws Exception
     */
    public function checkOtpValidity(UserOtpDto $userOtpDto): User
    {
        /**
         * @var ?User $user
         */
        $user = User::query()
            ->where('email', $userOtpDto->email)
            ->where('email_verification_code', $userOtpDto->otp)
            ->first();

        if (! $user) {
            throw new OtpInvalidException;
        }

        if ($user->email_verified_at instanceof Carbon) {
            throw new Exception('Email already verified');
        }

        if ($user->email_verification_code_expiry->isPast()) {
            throw new OtpExpiredException;
        }

        $user->email_verification_code = null;
        $user->email_verification_code_expiry = null;
        $user->email_verified_at = Carbon::now();

        $user->save();

        Auth::login($user);

        return $user;
    }

    /**
     * @throws Exception
     */
    public function resendOtp(string $email): void
    {
        /** @var User $user */
        $user = User::query()
            ->where('email', $email)
            ->firstOrFail();

        if ($user->email_verified_at) {
            throw new Exception('Email already verified');
        }

        $user->email_verification_code = rand(100000, 999999);
        $user->email_verification_code_expiry = Carbon::now()->addMinutes(5);

        $user->save();

        $this->sendOtp($user->email, $user->email_verification_code);
    }

    public function sendOtp(string $email, int $otp)
    {
        return 'TODO OTP MAIL"';
    }
}
