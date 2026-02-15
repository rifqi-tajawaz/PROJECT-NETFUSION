<?php

namespace App\Http\Requests\NetFusion;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotspotUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|min:3|max:50',
            'password' => 'required|string|min:3',
            'profile' => 'required|string',
            'server' => 'nullable|string',
            'comment' => 'nullable|string|max:100',
            'limit_uptime' => 'nullable|string',
            'limit_bytes_total' => 'nullable|numeric|min:0',
            'limit_bytes_unit' => 'nullable|in:MB,GB',
        ];
    }
}
