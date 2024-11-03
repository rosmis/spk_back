<?php

namespace App\Http\Controllers;

use App\Dto\User\UserRegisterDto;
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

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        /**
         * @var User $user
         */
        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();

        /*if ($user->email_verification_code_expiry) {
            return response()
                ->json('Please verify your email address', JsonResponse::HTTP_FORBIDDEN);
        }*/

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid login details',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $request->session()->regenerate();

        return response()->json(['message' => 'connected'], Response::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()
            ->json('disconnected', JsonResponse::HTTP_NO_CONTENT);
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

        return response()
            ->json($user, JsonResponse::HTTP_CREATED);
    }

    public function checkOtpValidity(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'integer'],
        ]);

        /**
         * @var ?User $user
         */
        $user = User::query()
            ->where('email', $request->string('email')->value)
            ->where('email_verification_code', $request->integer('otp'))
            ->first();

        if (! $user) {
            throw new OtpInvalidException('Votre code OTP est invalide. Veuillez réessayer.');
        }

        if ($user->email_verification_code_expiry->isPast()) {
            throw new OtpExpiredException('Votre code OTP a expiré. Veuillez en générer un nouveau.');
        }

        $user->email_verification_code = null;
        $user->email_verification_code_expiry = null;
        $user->email_verified_at = now();

        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return response()
            ->json($user, JsonResponse::HTTP_OK);
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
