<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'external_id' => ['required', 'string', 'unique:users,external_id'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
        ];
    }
}
