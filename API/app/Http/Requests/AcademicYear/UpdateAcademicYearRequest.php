<?php

namespace App\Http\Requests\AcademicYear;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicYearRequest extends FormRequest
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
                'max:100'
            ],

            'start_date' => [
                'required',
                'date'
            ],

            'end_date' => [
                'required',
                'date'
            ],

            'is_active' => [
                'required',
                'boolean'
            ]

        ];
    }
}
