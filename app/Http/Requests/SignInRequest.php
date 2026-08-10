<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class SignInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    #[Override]
    public function attributes()
    {
        return [
            'password' => 'contraseña'
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'email.exists' => 'El correo no esta registrado'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required','email', 'exists:users,email'],
            'password'=> 'required'
        ];
    }
}
