<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function guest()
    {
        return response()->json([
            "message" => "this is guest route and guest function",
        ]);
    }
    public function login(AuthRequest $repuest)
    {
        try {
            if (!Auth::attempt($repuest->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            } else {
                $user = User::where('email', $repuest->email)->first();
                return response()->json([
                    'status'  => true,
                    'message' => 'User Logged In Successfully',
                    'access_token'   => $user->createToken('API TOKEN')->plainTextToken,
                ], 200);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            "message" => "User Logout is successfully",
        ]);
    }

    public function user()
    {
        return response()->json([
            'user' => new UserResource(auth()->user())
        ]);
    }
}
