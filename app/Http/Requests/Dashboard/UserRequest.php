<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
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
        switch ($this->method()) {
            case 'POST':
                {
                    return [
                        'name' => ['required', 'min:3','max:225'],
                        'email' => ['required',Rule::unique('users'), 'min:3','max:225'],
                        'password' => ['required',Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                        'role_id' => 'required|exists:roles,id',
                    ];
                }
            case 'PATCH':
            case 'PUT':
                {
                    return [
                        'name' => ['required', 'min:3','max:225'],
                        'email' => ['required',Rule::unique('users')->ignore($this->user), 'min:3','max:225'],
                        'role_id' => 'required|exists:roles,id',
                    ];
                }
            default: return [];
        }
    }
}
