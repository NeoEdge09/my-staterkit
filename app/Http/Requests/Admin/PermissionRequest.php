<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
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
            'name' => ['nullable', 'string', Rule::unique('permissions')->ignore($this->role)],
            'module' => ['required', 'string'],
            'guard_name' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'module' => 'Module',
            'guard_name' => 'Guard Name'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => ':attribute wajib diisi',
            'name.string' => ':attribute harus berupa string',
            'name.unique' => ':attribute sudah terdaftar',
            'module.required' => ':attribute wajib diisi',
            'module.string' => ':attribute harus berupa string',
            'guard_name.required' => ':attribute wajib diisi',
            'guard_name.string' => ':attribute harus berupa string',
        ];
    }
}
