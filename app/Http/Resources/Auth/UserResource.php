<?php

namespace App\Http\Resources\Auth;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $permissionsArray = [];

        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permissions) {
                $permissionsArray[$permissions->name][] = $role->id;
            }
        }

        $permissions = array_keys($permissionsArray);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles->pluck('name'),
            'permissions' => $permissions
        ];
    }
}