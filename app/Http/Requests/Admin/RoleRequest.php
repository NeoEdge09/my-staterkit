<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
            'name' => ['required', 'string', Rule::unique('roles')->ignore($this->role)],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
            'guard_name' => ['required', 'string'],

        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'permissions' => 'Izin',
            'guard_name' => 'Guard Name'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => ':attribute wajib diisi',
            'name.string' => ':attribute harus berupa string',
            'name.unique' => ':attribute sudah terdaftar',
            'permissions.required' => ':attribute wajib diisi',
            'permissions.array' => ':attribute harus berupa array',
            'permissions.*.string' => 'Izin harus berupa string',
            'permissions.*.exists' => 'Izin tidak ditemukan',
            'guard_name.required' => ':attribute wajib diisi',
            'guard_name.string' => ':attribute harus berupa string',
        ];
    }
}
