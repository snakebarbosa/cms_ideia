<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class ParceiroRequest extends FormRequest
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

                    'titulo' =>'required|max:255',
                   // 'ativado'  =>'required',
                    'idimagem' =>'required',
                    
                ];
            }

            case 'PUT' : 
            case 'PATCH' : 
            {

                return [
                    
                  'titulo' =>'required|max:255',
                 // 'ativado'  =>'required',
                  'idimagem' =>'required',

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
        

    ];
}


}
