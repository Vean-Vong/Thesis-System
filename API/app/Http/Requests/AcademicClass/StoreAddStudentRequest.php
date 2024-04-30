<?php

namespace App\Http\Requests\AcademicClass;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddStudentRequest extends FormRequest
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
                'exists:academic_classes,id'
            ],

            'student_ids' => [
                'required',
                'array'
            ],

            'student_ids.*' => [
                'required',
                'exists:students,id'
            ]
        ];
    }
}
