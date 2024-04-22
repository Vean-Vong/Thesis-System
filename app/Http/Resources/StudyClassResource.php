<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyClassResource extends JsonResource
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
            'name' => $this->name,
            'teacher_id' => $this->teacher_id,
            'study_year_id' => $this->study_year_id,
            'grade_id' => $this->grade_id,
            'room_id' => $this->room_id,
            'status' => $this->status,
            'subject_ids' => $this->subjects->pluck('id'),
            'studies' => $this->studies->load('student'),
            'student_ids' => $this->studies->pluck('student_id')
        ];
    }
}
