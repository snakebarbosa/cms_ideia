<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
        return [
            'current_password' => 'required|string|min:6',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/'
            ],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'current_password.required' => 'Password atual é obrigatória',
            'current_password.min' => 'Password atual deve ter no mínimo 6 caracteres',
            
            'new_password.required' => 'Nova password é obrigatória',
            'new_password.min' => 'Nova password deve ter no mínimo 8 caracteres',
            'new_password.confirmed' => 'A confirmação da nova password não corresponde',
            'new_password.regex' => 'A password deve conter pelo menos: 1 letra maiúscula, 1 letra minúscula, 1 número e 1 símbolo especial (@$!%*?&#)',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'current_password' => 'password atual',
            'new_password' => 'nova password',
        ];
    }
}
