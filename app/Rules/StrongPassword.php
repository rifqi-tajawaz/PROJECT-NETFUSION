<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

/**
 * Strong Password Validation Rule
 *
 * Enforces strong password requirements:
 * - Minimum 8 characters
 * - At least 1 uppercase letter
 * - At least 1 lowercase letter
 * - At least 1 number
 * - At least 1 special character
 * - Not a common password
 * - Not similar to username
 * - Not similar to email
 */
class StrongPassword implements Rule
{
    /**
     * The error message for when validation fails.
     */
    protected string $errorMessage = 'The :attribute is not strong enough.';

    /**
     * The user model instance.
     */
    protected $user;

    /**
     * Create a new rule instance.
     *
     * @param  mixed  $user
     * @return void
     */
    public function __construct($user = null)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // Check minimum length
        if (strlen($value) < 8) {
            $this->errorMessage = 'The :attribute must be at least 8 characters.';
            return false;
        }

        // Check for uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            $this->errorMessage = 'The :attribute must contain at least 1 uppercase letter.';
            return false;
        }

        // Check for lowercase letter
        if (!preg_match('/[a-z]/', $value)) {
            $this->errorMessage = 'The :attribute must contain at least 1 lowercase letter.';
            return false;
        }

        // Check for number
        if (!preg_match('/[0-9]/', $value)) {
            $this->errorMessage = 'The :attribute must contain at least 1 number.';
            return false;
        }

        // Check for special character
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $value)) {
            $this->errorMessage = 'The :attribute must contain at least 1 special character (!@#$%^&*).';
            return false;
        }

        // Check for common passwords
        if ($this->isCommonPassword($value)) {
            $this->errorMessage = 'The :attribute is too common. Please choose a more secure password.';
            return false;
        }

        // Check similarity to username (if user available)
        if ($this->user && method_exists($this->user, 'name')) {
            $username = strtolower($this->user->name);
            $password = strtolower($value);

            // Calculate similarity
            similar_text($username, $password, $percent);
            if ($percent > 60) {
                $this->errorMessage = 'The :attribute is too similar to your username.';
                return false;
            }
        }

        // Check similarity to email (if user available)
        if ($this->user && method_exists($this->user, 'email')) {
            $emailParts = explode('@', $this->user->email);
            $emailUsername = strtolower($emailParts[0] ?? '');
            $password = strtolower($value);

            similar_text($emailUsername, $password, $percent);
            if ($percent > 60) {
                $this->errorMessage = 'The :attribute is too similar to your email.';
                return false;
            }
        }

        // Check for sequential characters (e.g., "123456", "abcdef")
        if ($this->hasSequentialChars($value)) {
            $this->errorMessage = 'The :attribute contains sequential characters (e.g., "123", "abc").';
            return false;
        }

        // Check for repeated characters (e.g., "111111", "aaaaaa")
        if ($this->hasRepeatedChars($value)) {
            $this->errorMessage = 'The :attribute contains repeated characters.';
            return false;
        }

        return true;
    }

    /**
     * Check if the password is a common password.
     *
     * @param  string  $password
     * @return bool
     */
    protected function isCommonPassword(string $password): bool
    {
        // List of common passwords (top 100)
        $commonPasswords = [
            'password', '12345678', '123456789', 'qwerty123',
            'abc12345', 'password1', 'password123', 'admin123',
            '1234567890', 'password123456', 'welcome123',
            'monkey123', 'football123', 'master123', 'letmein123',
            'shadow123', 'superman123', 'batman123', 'trustno1',
            'password1', 'password123', 'password12345', 'password6',
            'iloveyou', 'princess', 'rockyou', 'adobe123',
            '123123', 'admin', 'welcome', 'login', 'qwerty',
            'passw0rd', 'password!', 'hello123', 'football',
            'baseball', 'superman', 'iloveyou', 'starwars',
            'test123', 'test1234', 'test12345', 'example',
        ];

        return in_array(strtolower($password), $commonPasswords);
    }

    /**
     * Check if the password has sequential characters.
     *
     * @param  string  $password
     * @return bool
     */
    protected function hasSequentialChars(string $password): bool
    {
        $length = strlen($password);

        // Check for numeric sequences (123, 234, 345, etc.)
        for ($i = 0; $i < $length - 3; $i++) {
            if (
                is_numeric($password[$i]) &&
                is_numeric($password[$i + 1]) &&
                is_numeric($password[$i + 2]) &&
                is_numeric($password[$i + 3])
            ) {
                $isSequential = true;
                for ($j = 0; $j < 3; $j++) {
                    if ((int)$password[$i + $j] + 1 !== (int)$password[$i + $j + 1]) {
                        $isSequential = false;
                        break;
                    }
                }
                if ($isSequential) {
                    return true;
                }
            }

            // Check for alphabetic sequences (abc, bcd, cde, etc.)
            if (
                ctype_alpha($password[$i]) &&
                ctype_alpha($password[$i + 1]) &&
                ctype_alpha($password[$i + 2]) &&
                ctype_alpha($password[$i + 3])
            ) {
                $isSequential = true;
                for ($j = 0; $j < 3; $j++) {
                    if (ord($password[$i + $j]) + 1 !== ord($password[$i + $j + 1])) {
                        $isSequential = false;
                        break;
                    }
                }
                if ($isSequential) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if the password has repeated characters.
     *
     * @param  string  $password
     * @return bool
     */
    protected function hasRepeatedChars(string $password): bool
    {
        // Check for 4 or more repeated characters
        return preg_match('/(.)\1{3,}/', $password) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->errorMessage;
    }

    /**
     * Replace placeholders with custom attributes.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return string
     */
    public function replacer($validator): string
    {
        return str_replace(
            ':attribute',
            $this->displayAttributeValue($validator),
            $this->message()
        );
    }

    /**
     * Get the displayable name of the attribute.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return string
     */
    protected function displayAttributeValue($validator): string
    {
        return $validator->getDisplayableAttribute($this->attribute) ?: 'password';
    }
}
