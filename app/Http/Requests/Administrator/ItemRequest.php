<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
              
                'tituloPT' => 'required|max:255',
                'tituloEN' => 'required|max:255',
              //  'ativado'  => 'required',
                'url'      => 'required',
                'parent'   => 'required',
                'idTipo'   => 'required',
        
            ];
        }

        case 'PUT' : 
        case 'PATCH' : 
        {

            return [
                    
                'tituloPT' => 'required|max:255',
                'tituloEN' => 'required|max:255',
              //  'ativado'  => 'required',
                'url'      => 'required',
                'parent'   => 'required',
                'idTipo'   => 'required',

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
            'tituloPT.required'    => 'Titulo PT é obrigatorio',
            'tituloEN.required'    => 'Titulo EN é obrigatorio',
            'url.required' => 'Link é obrigatorio',           
            'idTipo.required' => 'Tag é obrigatorio',
            'parent.required'=>'Aninhado em é obrigatorio',


        ];
    }

}
