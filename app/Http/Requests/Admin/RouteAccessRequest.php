<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RouteAccessRequest extends FormRequest
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
            'route_name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'exists:roles,id'],
            'permission' => ['required', 'exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'role.exists' => 'The selected role is invalid.',
            'permission.exists' => 'The selected permission is invalid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'route_name' => 'route name',
            'role' => 'role',
            'permission' => 'permission',
        ];
    }
}
