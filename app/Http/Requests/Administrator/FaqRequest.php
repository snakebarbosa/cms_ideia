<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
                    'textoen'  =>'required',
                    'textopt'  =>'required',
                  //  'ativado'  =>'required',
                    'idCategoria'=>'required',
                   // 'destaque' =>'required'

                ];
            }

            case 'PUT' : 
            case 'PATCH' : 
            {

                return [
                    'tituloPT' => 'required|max:255',
                    'tituloEN' => 'required|max:255',
                    'textoen'  =>'required',
                    'textopt'  =>'required',
                 // 'ativado'  =>'required',
                    'idCategoria'=>'required',
                 // 'destaque' =>'required'

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
        'textoen.required' => 'Resposta (Inglês) é obrigatorio',
        'textopt.required' => 'Resposta (Português) é obrigatorio',
        'idCategoria.required' => 'Categoria obrigatorio',


    ];
}
}
