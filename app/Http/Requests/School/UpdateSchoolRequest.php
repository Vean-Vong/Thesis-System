<?php

namespace App\Http\Requests\School;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolRequest extends FormRequest
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

            "code" => [
                'required',
                Rule::unique('schools', 'code')->ignore(request('id')),
                'max:15'
            ],

            "khmer_name" => [
                'required',
                'max:255'
            ],

            "latin_name" => [
                'nullable',
                'max:255'
            ],

            "short_name" => [
                'required',
                'max:50'
            ],

            'sort' => [
                'integer',
                'nullable'
            ],

        ];
    }
}
