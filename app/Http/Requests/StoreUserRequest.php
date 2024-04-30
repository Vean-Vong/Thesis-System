<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'school_id' => ['sometimes', 'exists:schools,id'],
            "username" => ['required', 'unique:users,username'],
            "email" => ['required', 'email', 'unique:users,email'],
            "photo_path" => ['nullable', 'image'],
            "role_id" => ['required', 'exists:roles,id']
        ];
    }
}
