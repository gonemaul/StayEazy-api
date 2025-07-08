<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login berhasil"),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function register(Request $request)
    {
        return AuthService::register($request);
    }

    public function login(Request $request)
    {
        return AuthService::login($request);
    }

    public function logout(Request $request)
    {
        return AuthService::logout($request);
    }
}
