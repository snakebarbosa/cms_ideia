<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class LinkRequest extends FormRequest
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

                'titulo'    =>'required|max:255',
               // 'ativado'   =>'required',
                'url'       =>'required|URL',
                'idCategoria'=>'required',
               // 'destaque'  =>'required'

            ];
        }

        case 'PUT' : 
        case 'PATCH' : 
        {

            return [
              'titulo'   =>'required|max:255',
             // 'ativado'  =>'required',
              'url'      =>'required|URL',
              'idCategoria'=>'required',
             // 'destaque' =>'required'
          ];
      }
                # code...
      default: break;
  }

}


public function messages()
{
    return [
        'titulo.required' => 'Titulo PT é obrigatorio',
        'url.required' => 'url é obrigatorio.E deve ser do tipo (http://www.site.com)',
        'idCategoria.required' => 'Categoria é obrigatorio',
        
    ];
}
}
