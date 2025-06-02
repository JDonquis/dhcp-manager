<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtener el usuario de la ruta de manera más segura
        $user = $this->route('user');
        $userId = $user ? $user->id : null;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:50',
                'min:3',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s\-]+$/'
            ],
            'username' => [
                'required',
                'string',
                'max:20',
                'min:4',
                'regex:/^[A-Za-z0-9_]+$/',
                Rule::unique('users')->ignore($userId)
            ],
        ];

        // Reglas condicionales para password
        if ($this->isMethod('post')) {
            // Creación - password requerido
            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
                'confirmed'
            ];
        } else {
            // Actualización - password opcional
            if ($this->filled('password')) {
                $rules['password'] = [
                    'string',
                    'min:8',
                    'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
                    'confirmed'
                ];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre completo es obligatorio.',
            'name.string' => 'El nombre debe ser texto válido.',
            'name.max' => 'El nombre no puede exceder los 50 caracteres.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',

            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.string' => 'El nombre de usuario debe ser texto válido.',
            'username.max' => 'El nombre de usuario no puede exceder los 20 caracteres.',
            'username.min' => 'El nombre de usuario debe tener al menos 4 caracteres.',
            'username.regex' => 'El nombre de usuario solo puede contener letras, números y guiones bajos.',
            'username.unique' => 'Este nombre de usuario ya está en uso.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser texto válido.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una letra y un número.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('username')) {
            $this->merge([
                'username' => strtolower($this->username)
            ]);
        }

        // Eliminar campos de contraseña si están vacíos en actualización
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if (empty($this->password)) {
                $this->request->remove('password');
                $this->request->remove('password_confirmation');
            }
        }
    }
}