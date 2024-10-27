<?php

namespace App\Features\Users\Http\v1\Controllers;

use App\Features\Users\Domains\Models\User;
use App\Features\Users\Http\v1\Controllers\Services\AuthService;
use App\Features\Users\Http\v1\Data\LoginData;
use App\Features\Users\Http\v1\Data\UserData;
use App\Helpers\Services\ResponseHelper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param UserData $userData
     *
     * @return JsonResponse
     * @throws ValidationException|Exception
     */
    public function register(UserData $userData): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->authService->register(userData: $userData);
        $token = JWTAuth::fromUser($user);
        return  ResponseHelper::created(['user' => UserData::from($user)->except('password'), 'token' => $token]);
    }

    /**
     * @param LoginData $loginData
     *
     * @return JsonResponse
     * @throws ValidationException|Exception|AuthenticationException
     */
    public function login(LoginData $loginData): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $this->authService->login($loginData);
        $token = JWTAuth::fromUser($user);
        return ResponseHelper::success(message: 'Successfully logged in', data: ['user' => UserData::from(Auth::guard('api')->user())->except('password'), 'token' => $token]);
    }


    /**
     * @return JsonResponse
     */
    public function update(UserData $userData): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = JWTAuth::parseToken()->authenticate();
        $this->authService->update(user: $user, userData: $userData);
        return ResponseHelper::updated();
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->authService->logout();
    }

    /**
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $user = $this->authService->show();
        return ResponseHelper::success(UserData::from($user)->except('password'));
    }
}
