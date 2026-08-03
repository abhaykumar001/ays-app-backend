<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $agentId = $this->route('agent');

        return [
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'designation'     => ['nullable', 'string', 'max:255'],
            'email'           => [
                'required', 'email', 'max:255',
                Rule::unique('agents', 'email')->ignore($agentId),
            ],
            'phone'           => ['nullable', 'string', 'max:50'],
            'whatsapp'        => ['nullable', 'string', 'max:50'],
            'nationality'     => ['nullable', 'string', 'max:100'],
            'license_number'  => ['nullable', 'string', 'max:100'],
            'license_expiry'  => ['nullable', 'date'],
            'notes'           => ['nullable', 'string'],
            'is_active'       => ['nullable', 'boolean'],
            'image'           => ['nullable', 'image', 'max:5120'],
        ];
    }
}
