<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user)
            ],
            'password' => [
                $this->routeIs('admin.users.store') ? 'required' : 'nullable',
                'string',
                'min:8',
                'confirmed'
            ],
        ];
    }


    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => ':attribute tidak boleh kosong',
            'name.string' => ':attribute harus berupa string',
            'name.max' => ':attribute maksimal 255 karakter',
            'email.required' => ':attribute tidak boleh kosong',
            'email.string' => ':attribute harus berupa string',
            'email.email' => ':attribute harus berupa email',
            'email.max' => ':attribute maksimal 255 karakter',
            'email.unique' => ':attribute sudah terdaftar',
            'password.required' => ':attribute tidak boleh kosong',
            'password.string' => ':attribute harus berupa string',
            'password.min' => ':attribute minimal 8 karakter',
            'password.confirmed' => ':attribute tidak cocok',
        ];
    }
}
