<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Override;

class SignupRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'email' => ['required', 'email','unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->symbols()->uncompromised()]
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'EL nombre es obligatorio',
            'name.required' => 'EL nombre es obligatorio',
            'email.unique' => 'Este correo ya esta registrado',
            'password.min' => 'La contrasena debe tener :min caracteres'
        ];
    }
}
