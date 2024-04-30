<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'academic_class_id' => [
                'required',
                'exists:academic_classes,id',
            ],

            'month' => [
                'required',
                Rule::in([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])
            ],

            'attendances' => [
                'required'
            ],

            'attendances.*.days.*.absent' => [
                Rule::in(['P', 'A', ''])
            ]
        ];
    }
}
