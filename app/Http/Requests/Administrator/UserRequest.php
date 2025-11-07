<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6|confirmed',
                    'roles' => 'required',
                ];

            case 'PUT':
            case 'PATCH':
                $userId = $this->route('User');
                
                return [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
                    'roles' => 'required',
                ];

            default:
                return [];
        }
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Nome é obrigatorio',
            'name.string' => 'Nome deve ser texto',
            'name.max' => 'Nome não pode ter mais de 255 caracteres',
            
            'email.required' => 'E-Mail é obrigatorio',
            'email.email' => 'E-Mail deve ser válido',
            'email.max' => 'E-Mail não pode ter mais de 255 caracteres',
            'email.unique' => 'Este E-Mail já está registrado',
            
            'password.required' => 'Password é obrigatória',
            'password.min' => 'Password deve ter no mínimo 6 caracteres',
            'password.confirmed' => 'A confirmação de password não corresponde',
            
            'roles.required' => 'Deve selecionar pelo menos uma função',
        ];
    }
}
