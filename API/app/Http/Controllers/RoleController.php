<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{

    public function list(Request $request)
    {

        $result['status'] = 200;

        try {

            $roles = Role::latest()->paginate($request->perPage);

            $result['data'] = $roles;
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function store(StoreRoleRequest $request)
    {

        $result['status'] = 200;

        try {

            $role = Role::create([
                'name' => $request->name
            ]);

            foreach ($request->permissions as $permission_id) {
                PermissionRole::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }

            $result['data'] = new RoleResource($role);
            $result['message'] = "បង្កើតតួនាទីបានសម្រេច";
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function show(Request $request)
    {

        $result['status'] = 200;

        try {

            $role = Role::findOrFail($request->id);

            $result['data'] = new RoleResource($role);
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function update(UpdateRoleRequest $request)
    {

        $result['status'] = 200;

        try {

            $role = Role::findOrFail($request->id);
            $role->name =  $request->name;
            $role->save();

            DB::table('permission_role')->whereRoleId($role->id)->delete();
            foreach ($request->permissions as $permission_id) {
                PermissionRole::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }

            $result['data'] = new RoleResource($role);
            $result['message'] = "កែប្រែតួនាទីបានសម្រេច";
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $result['status'] = 200;

        try {

            $role = Role::findOrFail($request->id);

            DB::table('permission_role')->whereRoleId($role->id)->delete();

            $role->delete();

            $result['message'] = "លុបតួនាទីបានសម្រេច";
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
