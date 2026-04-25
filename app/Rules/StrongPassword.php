<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    public function passes($attribute, $value)
    {
        // Minimum 8 characters
        if (strlen($value) < 8) {
            return false;
        }

        // At least one uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            return false;
        }

        // At least one lowercase letter
        if (!preg_match('/[a-z]/', $value)) {
            return false;
        }

        // At least one number
        if (!preg_match('/[0-9]/', $value)) {
            return false;
        }

        // At least one special character
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $value)) {
            return false;
        }

        // No spaces
        if (preg_match('/\s/', $value)) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
    }
}
