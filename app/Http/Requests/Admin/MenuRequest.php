<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'route' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'permission' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
            'parent_id' => ['nullable', 'integer', 'exists:menus,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama uenu',
            'icon' => 'Icon menu',
            'route' => 'route',
            'role' => 'Role',
            'permission' => 'Izin',
            'order' => 'Urutan',
            'parent_id' => 'Menu induk',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama menu wajib diisi.',
            'parent_id.exists' => 'Menu induk tidak ditemukan.',
        ];
    }
}
