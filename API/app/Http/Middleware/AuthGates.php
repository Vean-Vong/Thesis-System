<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {            

            $roles = Role::with('permissions')->get();
            $permissionsArray = [];

            foreach ($roles as $role) {
                foreach ($role->permissions as $permissions) {                    
                    $permissionsArray[$permissions->name][] = $role->id;
                }
            }

            foreach ($permissionsArray as $name => $roles) {                
                Gate::define($name, function ($user) use ($roles) {                                                  
                    return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
                });
            }
        }

        return $next($request);
    }
}
