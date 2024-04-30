<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Organization;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function list(Request $request)
    {

        abort_if(Gate::denies('user_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $users = User::with('roles', 'school')->when(!auth()->user()->is_super, function ($q) {
                return $q
                    ->mine();
            })->filter(['search' => $request->search])->where('is_super', '=', false)->latest()->paginate($request->perPage);

            $result['data'] = $users;
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function store(StoreUserRequest $request)
    {

        abort_if(Gate::denies('user_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $school_id = Auth::user()->school_id;

            if ($request->school_id) {
                $school_id = $request->school_id;
            }

            $user = User::create([
                'school_id' => $school_id,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => "Password@123"
            ]);

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => $request->role_id
            ]);

            $result['data'] = new UserResource($user);
            $result['message'] = "បង្កើតអ្នកប្រើប្រាស់បានសម្រេច";
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
    public function show(Request $request)
    {

        abort_if(Gate::denies('user_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $user = User::findOrFail($request->id);

            $result['data'] = new UserResource($user);
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function update(UpdateUserRequest $request)
    {

        abort_if(Gate::denies('user_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $user = User::findOrFail($request->id);

            $user->update($request->all());

            DB::table('role_user')->whereUserId($user->id)->delete();

            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => $request->role_id
            ]);

            $result['data'] = new UserResource($user);
            $result['message'] = "កែប្រែអ្នកប្រើប្រាស់បានសម្រេច";
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function delete(Request $request)
    {

        abort_if(Gate::denies('user_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $user = User::findOrFail($request->id);

            $user->delete();

            DB::table('role_user')->whereUserId($user->id)->delete();

            $result['message'] = "លុបអ្នកប្រើប្រាស់បានសម្រេច";
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function resetPassword(Request $request)
    {

        abort_if(Gate::denies('user_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $user = User::findOrFail($request->id);

            $user->password = 'Password@123';

            $user->save();

            $result['message'] = "ផ្លាស់ប្តូរលេខសម្ងាត់បានសម្រេច";
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function callOrgRole(Request $request)
    {

        abort_if(Gate::denies('user_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $roles = Role::when(auth()->user()->is_super == false, function ($q) {
                return $q
                    ->where('id', '!=', '4ccadd62-e9f4-4bfb-b0c1-4ac00b129be6');
            })->get();
            $schools = School::orderBy('sort')->get();

            $result['data'] = [
                'roles' => $roles,
                'schools' => $schools
            ];
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
