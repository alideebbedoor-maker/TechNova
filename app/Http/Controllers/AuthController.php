<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{public function register(RegisterRequest $request): JsonResponse
{
    $validated = $request->validated();
    $validated['password'] = Hash::make($validated['password']);
    $validated['role'] = 'reader';

    $user = User::create($validated);
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'User registered successfully',
        'user'    => new UserResource($user), // جعلنا الـ Resource نظيفاً
        'token'   => $token                   // التوكن بجانبه
    ], 201);
}
    public function login(LoginRequest $request): JsonResponse
    {
        // تخزين البيانات المفلترة لضمان عدم قراءة الـ Request الخام مباشرة
        $validated = $request->validated();

        // البحث باستخدام الإيميل المفلتر
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'user'    => new UserResource($user),
            'token'   => $token
        ], 200);
    }
}   