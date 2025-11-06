<?php

namespace App\Http\Requests\Administrator;

use App\Rules\DataValidacao;
use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
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

                    'tituloPT' =>'required|max:255',
                    'tituloEN' =>'required|max:255',
                    'textoen'  =>'required',
                    'textopt'  =>'required',
                   // 'ativado'  =>'required',
                    'url'  =>'required',
                    'idimagem' =>'required',
                    'publicar' => 'before:despublicar',
                ];
            }

            case 'PUT' : 
            case 'PATCH' : 
            {

                return [

                 'tituloPT' =>'required|max:255',
                 'tituloEN' =>'required|max:255',
                 'textoen'  =>'required',
                 'textopt'  =>'required',
                // 'ativado'  =>'required',
                 'url'  =>'required',
                 'idimagem' =>'required',
                'publicar' => 'before:despublicar',

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
        'titulopt.required' => 'Titulo PT é obrigatorio',
        'tituloen.required' => 'Titulo EN é obrigatorio',
        'textoen.required' => 'Descrição (Inglês) é obrigatorio',
        'textopt.required' => 'Descrição (Português) é obrigatorio',
        'url.required'  => 'URl (Link) é obrigatorio',
        'idimagem.required'=> 'Imagem é obrigatorio',
        'publicar.before' => 'Data de publicação tem que ser antes da data de remoção.',

    ];
}
}
