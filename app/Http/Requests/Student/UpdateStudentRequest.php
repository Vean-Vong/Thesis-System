<?php

namespace App\Http\Requests\Student;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'code'              => [Rule::unique('students')->ignore(request('id'), 'id'), 'required', 'max:20'],
            'first_name'        => ['required', 'max:50'],
            'last_name'         => ['required', 'max:50'],
            'first_name_latin'  => ['required', 'max:50'],
            'last_name_latin'   => ['required', 'max:50'],
            'gender'            => ['required', Rule::in([1, 2, 3])],
            'dob'               => ['required', 'date'],
            'from'              => ['nullable', 'max:255'],
            'photo_path'        => ['nullable', 'image'],
            'other'             => ['nullable', 'max:255'],
            'student_status'    => ['nullable', Rule::in([1, 2, 3, 4])],
            'status'            => ['nullable', Rule::in([1, 2])],
            'phone'             => ['nullable', 'max:50'],

            'village_birth'     => ['required', 'max:100'],
            'commune_birth'     => ['required', 'max:100'],
            'district_birth'    => ['required', 'max:100'],
            'province_birth'    => ['required', 'max:100'],

            'village'           => ['required', 'max:100'],
            'commune'           => ['required', 'max:100'],
            'district'          => ['required', 'max:100'],
            'province'          => ['required', 'max:100'],

            'd_first_name'      => ['nullable', 'max:50'],
            'd_last_name'       => ['nullable', 'max:50'],
            'd_job'             => ['nullable', 'max:50'],
            'd_phone_number'    => ['nullable', 'max:50'],

            'm_first_name'      => ['nullable', 'max:50'],
            'm_last_name'       => ['nullable', 'max:50'],
            'm_job'             => ['nullable', 'max:50'],
            'm_phone_number'    => ['nullable', 'max:50'],

            'g_first_name'      => ['nullable', 'max:50'],
            'g_last_name'       => ['nullable', 'max:50'],
            'g_phone_number'    => ['nullable', 'max:50'],
            'g_job'             => ['nullable', 'max:100'],
            'g_gender'          => ['nullable', Rule::in([1, 2])],
            'g_detail'          => ['nullable', 'max:255'],
        ];
    }
}