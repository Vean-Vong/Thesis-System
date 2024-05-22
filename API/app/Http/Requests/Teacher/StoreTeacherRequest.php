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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [

            'code' => [
                'required',
                'max:20',
                Rule::unique('teachers', 'code')
            ],

            'name' => [
                'required',
                'max:100'
            ],

            'name_latin' => [
                'nullable',
                'max:100'
            ],

            'sex' => [
                'required',
                Rule::in([1, 2])
            ],

            'position' => [
                'required',
                Rule::in([1, 2, 3])
            ],

            'dob' => [
                'nullable',
                'date',
            ],

            'photo_path' => [
                'nullable',
                'image'
            ],

            'pob' => [
                'nullable',
                'max:255'
            ],

            'address' => [
                'nullable',
                'max:255'
            ],

            'phone' => [
                'nullable',
                'max:50'
            ],

        ];
    }
}
