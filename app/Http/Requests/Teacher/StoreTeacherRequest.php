<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherRequest extends FormRequest
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
            'code' => [
                'required',
                Rule::unique('teachers', 'code'),
                'max:20'
            ],

            'first_name' => [
                'required',
                'max:50'
            ],

            'last_name' => [
                'required',
                'max:50'
            ],

            'first_name_latin' => [
                'required',
                'max:50'
            ],

            'last_name_latin' => [
                'required',
                'max:50'
            ],

            'gender' => [
                'required',
                Rule::in([1, 2])
            ],

            'dob' => [
                'nullable',
            ],

            'school_name' => [
                'nullable',
                'max:50'
            ],

            'school_code' => [
                'nullable',
                'max:50'
            ],

            'village' => [
                'nullable',
                'max:50'
            ],

            'commune' => [
                'nullable',
                'max:50'
            ],

            'district' => [
                'nullable',
                'max:50'
            ],

            'province' => [
                'nullable',
                'max:50'
            ],

            'phone' => [
                'required',
                'max:50'
            ],

            'photo_path' => [
                'nullable',
                'image'
            ],

            'status' => [
                'nullable'
            ]

        ];
    }
}
