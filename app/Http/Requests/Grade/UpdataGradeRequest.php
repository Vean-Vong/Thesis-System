<?php

namespace App\Http\Requests\Grade;

use Illuminate\Foundation\Http\FormRequest;

class UpdataGradeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'status' => ['required'],

            'subject_ids' => [
                'nullable',
                'array'
            ],

            'subject_ids.*' => [
                'nullable',
                'exists:subjects,id'
            ]
        ];
    }
}