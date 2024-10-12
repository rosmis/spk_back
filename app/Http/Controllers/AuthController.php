<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
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

    public function register(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string'],
        ]);

        $credentials['password'] = bcrypt($credentials['password']);

        /*$otp = rand(100000, 999999);
        $credentials['email_verification_code'] = $otp;
        $credentials['email_verification_code_expiry'] = now()->addMinutes(5);*/

        /**
         * @var User $user
         */
        $user = User::query()->create($credentials);

        /*$this->sendOtp($user->email, $otp);*/

        return response()
            ->json($user, JsonResponse::HTTP_CREATED);
    }
}
