<?php

namespace App\Features\Users\Http\v1\Controllers\Services;

use App\Features\Users\Domains\Models\User;
use App\Features\Users\Http\v1\Data\LoginData;
use App\Features\Users\Http\v1\Data\UserData;
use App\Features\Users\Http\v1\Data\UserRegistrationData;
use App\Helpers\Services\ResponseHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * @param UserData $userData
     *
     * @return User
     */
    public function register(UserData $userData): User
    {
        return User::persistUser(userData: $userData);
    }


    /**
     * @param User $user
     * @param UserData $userData
     *
     * @return bool
     */
    public function update(User $user, UserData $userData): bool
    {
        return $user->updateUser(userData: $userData);
    }

    /**
     * @param LoginData $loginData
     *
     * @return User
     */
    public function login(LoginData $loginData): User
    {
        $authAttempt = Auth::guard('api')->attempt($loginData->only('email', 'password')->toArray());
        if (!$authAttempt) {
            throw new AuthenticationException('Invalid Credentials');
        }
        return Auth::guard('api')->user();
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        JWTAuth::parseToken()->invalidate(true);
        return ResponseHelper::success(message: 'Successfully logged out');
    }

    /**
     * @return User
     */
    public function show(): User
    {
        return JWTAuth::parseToken()->authenticate();
    }

}
