<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'picture' => [],
        ];
    }
}
