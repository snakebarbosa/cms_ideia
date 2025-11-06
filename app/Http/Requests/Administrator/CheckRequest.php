<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class CheckRequest extends FormRequest
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
                    'check' => 'required',
               
                ]; 
            }
            
            case 'PUT' : 
            case 'PATCH' : 
            {      
                return [ 
                    'check' => 'required',
                   
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
            'check.required' => 'È obrigatorio ter pelo menos um campo selecionado',
        ];
           
    }
   } 