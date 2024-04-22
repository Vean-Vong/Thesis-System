<?php

namespace App\Http\Controllers;


use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    //function index list data

    public function index(Request $request)
    {

        $users = DB::table('users')

            ->when(!empty(request('search')), function ($query) use ($request) {
                return $query
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })

            ->paginate($request->per_page ?? 15);
        return response()->json([
            "data" => $users,
        ]);
    }

    //function create data

    public function create()
    {
        $roles = DB::table('roles')->get();
        return response()->json([
            'roles' => $roles,
        ]);
    }

    //function insert data

    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user           = new    User();
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->password = Hash::make('12345678');
            $user->save();
            foreach ($request->roles as $item) {
                DB::table('role_user')->insert([
                    'user_id' => $user->id,
                    'role_id' => $item,
                ]);
            };
            DB::commit();
            return response()->json([
                'message' => "successfully created user",
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //function edit data

    public function edit($id)
    {

        $user = DB::table('users')->where('id', $id)
            ->select('id', 'name', 'email', 'password')
            ->first();
        $role_user = DB::table('role_user')
            ->join('users', 'users.id', '=', 'role_user.user_id')
            ->where('role_user.user_id', $id)
            ->pluck('role_user.role_id');

        return response()->json([
            'data' => [
                'form' => [
                    'name'     => $user->name,
                    'email'    => $user->email,
                    'password' => $user->password,
                    'roles'    => $role_user,
                ],
            ],
        ]);
    }

    //function show data

    public function show(string $id)
    {
        //
    }

    //function updata data

    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->update();

            DB::table('role_user')
                ->where('user_id', $id)->delete();

            foreach ($request->roles as $item) {
                DB::table('role_user')->insert([
                    'user_id' => $user->id,
                    'role_id' => $item,
                ]);
            }
            DB::commit();
            return response()->json([
                "message" => "successfully updated user"
            ]);
        } catch (Throwable $e) {
            log::error($e);
            DB::rollBack();
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    //function delete data

    public function destroy(string $id)
    {
        DB::table('role_user')->where('role_user.user_id', $id)
            ->delete();
        DB::table('users')->where('id', $id)
            ->delete();
        return response()->json([
            "message" => "successfully deleted user",
        ]);
    }
}
