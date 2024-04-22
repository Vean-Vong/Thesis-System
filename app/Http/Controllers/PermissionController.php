<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $permission = DB::table('permissions')->latest()->get();
        return response()->json([
            'data' => $permission,
        ]);
    }
}
