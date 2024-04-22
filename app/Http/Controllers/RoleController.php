<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = DB::table('roles')
            ->select('id', 'display_name')
            ->when(request('search') ?? null, function ($query) {
                return $query
                    ->where('display_name', 'LIKE', '%' . request('search') . '%');
            })
            ->paginate(request('per_page', 15));
        return response()->json([
            'data' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissionOpt = DB::table('permissions')->select('id', 'display_name')->get();
        return response()->json([
            'permissionOpt' => $permissionOpt,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            DB::beginTransaction();
            $role = new Role();
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->save();
            foreach ($request->permission_ids as $item) {
                DB::table('permission_role')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $item,
                ]);
            }
            DB::commit();
            return response()->json([
                'message' => "successfully created role",
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = DB::table('roles')
            ->where('id', $id)
            ->select('name', 'display_name')
            ->first();

        $permission_role = DB::table('permission_role')
            ->join('roles', 'roles.id', 'permission_role.role_id')
            ->where('permission_role.role_id', $id)
            ->pluck('permission_role.permission_id');

        $permissionOpt = DB::table('permissions')->select('id', 'display_name')->get();

        return response()->json([
            'data' => [
                'form' => [
                    'name' => $role->name,
                    'display_name' => $role->display_name,
                    'permission_ids' => $permission_role,
                ],
                'permissionOpt' => $permissionOpt,
            ],

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->update();

            DB::table('permission_role')
                ->where('role_id', $role->id)->delete();

            foreach ($request->permission_ids as $item) {
                DB::table('permission_role')
                    ->insert([
                        'role_id' => $role->id,
                        'permission_id' => $item,
                    ]);
            }
            DB::commit();
            return response()->json([
                "message" => "successfully updated role",
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('permission_role')
            ->where('permission_role.role_id', $id)
            ->delete();
        DB::table('roles')
            ->where('id', $id)
            ->delete();
        return response()->json([
            "message" => "successfully deleted role",
        ]);
    }
}
