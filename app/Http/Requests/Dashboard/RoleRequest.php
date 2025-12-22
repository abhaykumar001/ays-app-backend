<?php

namespace App\Http\Requests\Dashboard;

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
    public function rules() : array
    {
        switch ($this->method()) {
            case 'POST':
                {
                    return [
                        'name' => ['required', Rule::unique('roles'),'max:225'],
                        'permissions' => ['required'],
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'name' => ['required',Rule::unique('roles')->ignore($this->role),'max:225'],
                        'permissions' => ['required'],
                    ];
                }
            default: return [];
        }
    }
}
