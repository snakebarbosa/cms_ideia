<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class ModuloRequest extends FormRequest
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
                
            'topo'      =>'required',
            'principal' =>'required',
            'navegacao' =>'required',
            'rodape'    =>'required',
           
            ];
        }

        case 'PUT' : 
        case 'PATCH' : 
        {

            return [
            'topo'      =>'required',
            'principal' =>'required',
            'navegacao' =>'required',
            'rodape'    =>'required',
               
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
          //  'titulopt.required' => 'Titulo PT é obrigatorio',
           
        ];
    }
}
