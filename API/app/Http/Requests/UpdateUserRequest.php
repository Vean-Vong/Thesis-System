<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return abort_if(Gate::denies('user_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            "email" => ['required', 'email', Rule::unique('users', 'email')->ignore(request('id'))],
            "username" => ['required', Rule::unique('users', 'username')->ignore(request('id'))],
            "photo_path" => ['nullable', 'image'],
            "role_id" => ['required', 'exists:roles,id']
        ];
    }
}
