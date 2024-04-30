<?php

namespace App\Http\Requests\AcademicClass;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            'name' => [
                'required',
                Rule::unique('academic_classes', 'name')->where('school_id', auth()->user()->school_id)->where('academic_year_id', request('academic_year_id'))->ignore(request('id'))
            ],

            'teacher_id' => [
                'required',
                'exists:teachers,id',
                // Rule::unique('academic_classes', 'teacher_id')->where('school_id', auth()->user()->school_id)->where('academic_year_id', request('academic_year_id'))->ignore(request('id'))
            ],

            'academic_year_id' => [
                'required',
                'exists:academic_years,id'
            ]

        ];
    }
}
