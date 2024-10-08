<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:40',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|max:20|confirmed',
        ];
    }
    public function messages() {
        return [
            'name.required' => 'Nombre es requerido',
            'name.min' => 'Nombre debe tener al menos 3 caracteres',
            'name.max' => 'Nombre debe tener como máximo 40 caracteres',
            'email.required' => 'Email es requerido',
            'email.email' => 'Email debe ser un email válido',
            'email.unique' => 'Email ya está en uso',
            'password.required' => 'Contraseña es requerida',
            'password.min' => 'Contraseña debe tener al menos 6 caracteres',
            'password.max' => 'Contraseña debe tener como máximo 20 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ];
    }
}
