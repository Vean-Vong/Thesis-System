<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExamRequest extends FormRequest
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

            'exams' => [
                'array'
            ],

            'exams.*.student_id' => [
                'required',
                'exists:students,id'
            ],

        ];
    }
}
