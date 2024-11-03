<?php

namespace App\Http\Controllers;

use App\Dto\User\UserLoginDto;
use App\Dto\User\UserOtpDto;
use App\Dto\User\UserRegisterDto;
use App\Exceptions\Auth\EmailNotVerifiedException;
use App\Exceptions\Auth\OtpExpiredException;
use App\Exceptions\Auth\OtpInvalidException;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        public readonly AuthService $authService
    ) {}

    /**
     * @throws Exception
     * @throws OtpExpiredException
     * @throws EmailNotVerifiedException
     */
    public function login(Request $request): JsonResponse
    {
        $userLoginData = UserLoginDto::fromArray(
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ])
        );

        $this->authService->login($userLoginData);

        $request->session()->regenerate();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws Exception
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register(
            UserRegisterDto::fromArray($request->validated()),
            $request->user()
        );

        return new JsonResponse($user, Response::HTTP_CREATED);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws Exception
     * @throws OtpExpiredException
     * @throws OtpInvalidException
     */
    public function checkOtpValidity(Request $request): JsonResponse
    {
        $otpData = UserOtpDto::fromArray(
            $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
                'otp' => ['required', 'integer'],
            ])
        );

        $user = $this->authService->checkOtpValidity($otpData);

        $request->session()->regenerate();

        return new JsonResponse($user, Response::HTTP_NO_CONTENT);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
        ]);

        /**
         * @var ?User $user
         */
        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();

        if ($user && $user->email_verification_code_expiry->isPast()) {
            $otp = rand(100000, 999999);
            $user->email_verification_code = $otp;
            $user->email_verification_code_expiry = now()->addMinutes(5);

            $user->save();

            $this->authService->sendOtp($user->email, $otp);

            return response()
                ->json('OTP sent', JsonResponse::HTTP_OK);
        }

        return response()
            ->json('User not found', JsonResponse::HTTP_NOT_FOUND);
    }
}
