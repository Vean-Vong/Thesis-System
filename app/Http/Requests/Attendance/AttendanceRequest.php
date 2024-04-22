<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date'
            ],

            'meridiem' => [
                'required',
                Rule::in([1, 2])
            ],

            'details' => [
                'required',
                'array'
            ],

            'details.*.student_id' => [
                'required',
                'exists:students,id'
            ],

            'details.*.absent' => [
                'nullable',
                Rule::in([1, 2])
            ],

            'details.*.id' => [
                'nullable',
                'exists:attendances,id'
            ]
        ];
    }
}
