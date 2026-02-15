<?php

namespace App\Http\Requests\NetFusion;

use Illuminate\Foundation\Http\FormRequest;

class StorePppSecretRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'password' => 'required|string',
            'profile' => 'required|string',
            'service' => 'required|string',
            'local_address' => 'nullable|ip',
            'remote_address' => 'nullable|ip',
            'comment' => 'nullable|string',
        ];
    }
}
