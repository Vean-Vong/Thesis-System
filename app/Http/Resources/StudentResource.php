<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'first_name_latin' => $this->first_name_latin,
            'last_name_latin' => $this->last_name_latin,

            'code' => $this->code,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'phone' => $this->phone,
            'student_status' => $this->student_status,
            'status' => $this->status,
            'from' => $this->from,
            'photo_path' => $this->photo_path,
            'other' => $this->other,

            'village_birth' => $this->village_birth,
            'commune_birth' => $this->commune_birth,
            'district_birth' => $this->district_birth,
            'province_birth' => $this->province_birth,

            'village' => $this->village,
            'commune' => $this->commune,
            'district' => $this->district,
            'province' => $this->province,

            'd_first_name' => $this->d_first_name,
            'd_last_name' => $this->d_last_name,
            'd_job' => $this->d_job,
            'd_phone_number' => $this->d_phone_number,

            'm_first_name' => $this->m_first_name,
            'm_last_name' => $this->m_last_name,
            'm_job' => $this->m_job,
            'm_phone_number' => $this->m_phone_number,

            'g_first_name' => $this->g_first_name,
            'g_last_name'  => $this->g_last_name,
            'g_job'        => $this->g_job,
            'g_phone_number' => $this->g_phone_number,
            'g_gender'     => $this->g_gender,
            'g_detail'     => $this->g_detail,

            'student_status_label' => $this->student_status_label,
            'gender_label' => $this->gender_label,
            'g_gender_label' => $this->guardian_gender_label,
            'status_label'  => $this->status_label,

            'full_name' => $this->full_name,
            'full_name_latin' => $this->full_name_latin,
            'mother_full_name' => $this->mother_full_name,
            'father_full_name' => $this->father_full_name,
            'guardian_full_name' => $this->guardian_full_name,
        ];
    }
}
