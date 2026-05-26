<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::latest()->get();

        return response()->json([
            'data' => UserResource::collection($users)
        ], 200);
    }

    public function changeRole(UpdateUserRoleRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $user = User::findOrFail($id);
        $user->update(['role' => $validated['role']]);

        return response()->json([
            'message' => "User role updated successfully to {$validated['role']}",
            'data'    => new UserResource($user)
        ], 200);
    }
}