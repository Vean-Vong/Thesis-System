<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangeProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\Contact;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $result = ['status' => 200];

        try {

            $user = User::whereUsername($request->email)->orWhere('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                $result['message'] = "The user credentials were incorrect.";
                $result['status'] = 201;
                return response()->json($result);
            }

            $token = $user->createToken('Api_Token')->plainTextToken;

            $result['user'] = new UserResource($user);
            $result['access_token'] = $token;
            $result['message'] = "Successfully Login.";
        } catch (Throwable $e) {
            $result['message'] = $e->getMessage();
            $result['status'] = 201;
        }

        return response()->json($result);
    }

    public function logout()
    {
        $result = ['status' => 200];
        try {

            Auth::user()->currentAccessToken()->delete();

            $result['message'] = "Successfully Logout.";
        } catch (Throwable $e) {
            $result['message'] = $e->getMessage();
            $result['status'] = 201;
        }

        return response()->json($result);
    }

    public function verifyAuth(Request $request)
    {
        $result = ['status' => 200];
        try {
            $result['user'] = new UserResource($request->user());
        } catch (Throwable $e) {
            $result['message'] = $e->getMessage();
            $result['status'] = 201;
        }
        return response()->json($result);
    }

    public function changeProfile(ChangeProfileRequest $request)
    {

        $result['status'] = 200;

        try {

            $user = $request->user();
            $user->email = $request->email;
            $user->photo_path = $request->photo_path;
            $user->save();

            $result['message'] = "កែប្រែពត៍មានផ្ទាល់ខ្លួនបានសម្រេច";
        } catch (Throwable $e) {
            $result['message'] = $e->getMessage();
            $result['status'] = 201;
        }


        return response()->json($result);
    }

    public function changePassword(ChangePasswordRequest $request)
    {

        $result['status'] = 200;

        try {

            $user = Auth::user();

            if (!$user || !Hash::check($request->old_password, $user->password)) {
                $result['message'] = "លេខសម្ងាត់ចាស់មិនត្រឹមត្រូវទេ";
                $result['status'] = 201;
                return response()->json($result);
            }

            $user->password = Hash::make($request->password);

            $user->save();

            $result['data'] = new UserResource($user);
            $result['message'] = "កែប្រែលេខសម្ងាត់បានសម្រេច";
        } catch (Throwable $e) {
            $result['message'] = $e->getMessage();
            $result['status'] = 201;
        }


        return response()->json($result);
    }
}
