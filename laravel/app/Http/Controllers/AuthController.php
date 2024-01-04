<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:155',
            'password' => 'required|min:6|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('user_name', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return $this->responseJsonError('Tên tài khoản hoặc mật khẩu đăng nhập không chính xác',401);
        }
        $user = Auth::user();
        $role = $user->roles()->first();
        $permissionUserLogin = $role->permissions->pluck('slug');
        $permissions = [];
        foreach ($permissionUserLogin as $value) {
            $permissions[$value] = true;
        }

        return response()->json([
            'code' => 200,
            'user' => $user,
            'permissions' => $permissions,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'code' => 401,
                'message' => 'Token is missing in the request',
            ], 401);
        }

        try {
            Auth::logout();

            return response()->json([
                'code' => 200,
                'message' => 'Successfully logged out',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
