<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;
use App\Rules\NotDisposableEmail;
use App\Rules\PasswordStrength;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                new NotDisposableEmail
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                new PasswordStrength(8, true, true, true, true, true)
            ],
            'g-recaptcha-response' => ['required', new Recaptcha],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.confirmed' => 'The password confirmation does not match.',
            'g-recaptcha-response.required' => 'Please refresh the page to verify you are not a robot.',
        ];
    }
}
