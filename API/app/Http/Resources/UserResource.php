<?php

namespace App\Http\Resources;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $permissions = Role::with('permissions')->find($this->roles[0]->id);

        return [
            "id" => $this->id,
            "name" => $this->name,
            "username" => $this->username,
            "email" => $this->email,
            "owner" => $this->owner,
            "is_admin" => $this->isDemoUser(),
            "school" => $this->school,
            "roles" => $this->roles,
            "permissions" => $permissions->permissions,
            "photo_path" => $this->photo_path
        ];
    }
}
