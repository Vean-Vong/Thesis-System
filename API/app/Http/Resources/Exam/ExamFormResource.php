<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamFormResource extends JsonResource
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
            'name' => $this['name'],
            'dob' => $this['dob'],
            'sex' => $this['sex'],
            'k' => $this['k'] ?? 0,
            'm' => $this['m'] ?? 0,
            'sc' => $this['sc'] ?? 0,
            's' => $this['s'] ?? 0,
        ];
    }
}
