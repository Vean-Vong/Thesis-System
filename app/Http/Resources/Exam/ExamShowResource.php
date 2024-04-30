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
            'name' => $this['name'],
            'dob' => $this['dob'],
            'sex' => $this['sex'],
            'k' => number_format($this['k'] ?? 0 ?? 0, 2, '.', ','),
            'm' => number_format($this['m'] ?? 0 ?? 0, 2, '.', ','),
            'sc' => number_format($this['sc'] ?? 0 ?? 0, 2, '.', ','),
            's' => number_format($this['s'] ?? 0, 2, '.', ','),
            'total' => number_format((isset($this['k']) ? $this['k'] : 0) + (isset($this['m']) ? $this['m'] : 0) + (isset($this['sc']) ? $this['sc'] : 0) + + (isset($this['s']) ? $this['s'] : 0), 2, '.', ','),
            'avg' => number_format(((isset($this['k']) ? $this['k'] : 0) + (isset($this['m']) ? $this['m'] : 0) + (isset($this['sc']) ? $this['sc'] : 0) + + (isset($this['s']) ? $this['s'] : 0)) / 4, 2, '.', ','),
            'result' => (((isset($this['k']) ? $this['k'] : 0) + (isset($this['m']) ? $this['m'] : 0) + (isset($this['sc']) ? $this['sc'] : 0) + + (isset($this['s']) ? $this['s'] : 0)) / 4) >= 5 ? 'ជាប់' : 'ធ្លាក់',
        ];
    }
}
