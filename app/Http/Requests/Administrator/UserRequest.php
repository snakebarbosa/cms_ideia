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
        {

            return [

                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',

            ];
        }

        case 'PUT' : 
        case 'PATCH' : 
        {

            return [
                  
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',

            ];
        }
                # code...
        default: break;
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
            'email.required' => 'E-Mail Address é obrigatorio',
            'password.required' => 'Password é obrigatorio',
         

        ];
    }

}
