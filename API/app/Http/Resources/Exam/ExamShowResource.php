<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'] ?? null,
            'student_id' => $this['student_id'],
            'last_name' => $this['last_name'],
            'first_name' => $this['first_name'],
            'gender' => $this['gender'],
            'att' => $this['att'] ?? 0,
            'quiz' => $this['quiz'] ?? 0,
            'hw' => $this['hw'] ?? 0,
            're' => $this['re'] ?? 0,
            'voc' => $this['voc'] ?? 0,
            'gr' => $this['gr'] ?? 0,
            'liu' => $this['liu'] ?? 0,
            'wr' => $this['wr'] ?? 0,
            'li' => $this['li'] ?? 0,
            'sp' => $this['sp'] ?? 0,
            'mid' => $this['mid'] ?? 0,
            'attitude' => $this['attitude'] ?? 0,
            'final' => $this['final'] ?? 0,
            'total' => $this['total'] ?? 0
        ];
    }
}
