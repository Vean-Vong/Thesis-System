<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "code" => $this->code,
            "last_name" => $this->last_name,
            "first_name" => $this->first_name,
            "last_name_latin" => $this->last_name_latin,
            "first_name_latin" => $this->first_name_latin,
            "gender" => $this->gender,
            "gender_label" => $this->gender_label,
            "full_name" => $this->full_name,
            "full_name_latin" =>  $this->full_name_latin,
            "dob" => $this->dob,
            "school_code" => $this->school_code,
            "school_name" => $this->school_name,
            "village" => $this->village,
            "commune" => $this->commune,
            "district" => $this->district,
            "province" => $this->province,
            "phone" => $this->phone,
            "photo_path" => $this->photo_path,
            "status" => $this->status == 1 ? true : false,
            "status_label" => $this->status_label,
        ];
    }
}
