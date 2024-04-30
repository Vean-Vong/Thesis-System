<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use Throwable;

class PermissionController extends Controller
{

    public function list()
    {
        $result = ['status' => 200];

        try {

            $permissions = Permission::whereNull('parent_id')->orderBy('name')->with('childrens')->get();

            $result['data'] = PermissionResource::collection($permissions);
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
