<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

/**
 * API Registration Request
 *
 * Handles validation for API registration requests with enhanced security.
 */
class ApiRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Guests can register, authenticated users cannot.
     */
    public function authorize(): bool
    {
        return true; // Allow guests to register
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[\pL\s\-\.]+$/u', // Only letters, spaces, hyphens, and dots
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed', // Requires password_confirmation field
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\+\-\(\)]+$/', // Phone number format
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 2 characters',
            'name.max' => 'Name must not exceed 255 characters',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and dots',
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email address is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'phone.regex' => 'Please provide a valid phone number',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'password' => 'password',
            'phone' => 'phone number',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Additional validation: Check if email is from disposable domain
            if ($validator->validated()['email'] ?? null) {
                $this->validateDisposableEmail($validator);
            }
        });
    }

    /**
     * Validate that email is not from a disposable email domain.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected function validateDisposableEmail($validator)
    {
        $email = $this->input('email');
        $domain = strtolower(substr(strrchr($email, '@'), 1));

        // List of common disposable email domains
        $disposableDomains = [
            'tempmail.com', 'guerrillamail.com', 'mailinator.com',
            '10minutemail.com', 'yopmail.com', 'maildrop.com',
            'throwaway.email', 'fakeinbox.com', 'tempmail.org',
        ];

        if (in_array($domain, $disposableDomains)) {
            $validator->errors()->add('email', 'Disposable email addresses are not allowed');
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'error' => 'validation_failed',
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors(),
                ], 422)
            );
        }

        throw new ValidationException($validator);
    }
}
