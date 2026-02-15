<?php

namespace App\Http\Requests\NetFusion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotspotUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile' => 'required|string',
            'password' => 'nullable|string|min:3',
            'server' => 'nullable|string',
            'comment' => 'nullable|string|max:100',
            'limit_uptime' => 'nullable|string',
            'limit_bytes_total' => 'nullable|numeric|min:0',
            'limit_bytes_unit' => 'nullable|in:MB,GB',
        ];
    }
}
