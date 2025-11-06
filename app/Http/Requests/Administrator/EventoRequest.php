<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class EventoRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {

		switch ($this->method()) {
			case 'POST':
				{

					return [

						'nome'       => 'required|max:255',
						'endereco'   => 'required',
						'dataInicio' => 'required|date',						
						'dataFim' =>'nullable|date|after:dataInicio',
						'precoIndividual' => 'integer|min:0|nullable',
						'dataPagamento' =>'nullable|date|before_or_equal:dataFim|required_with:precoIndividual',
						// 'numeroInscricao'  =>'required|numeric',
						//  'ativado'  =>'required',
						// 'destaque' =>'required'

					];
				}

			case 'PUT':
			case 'PATCH':
				{

					return [
						'nome'       => 'required|max:255',
						'endereco'   => 'required',
						'dataInicio' => 'required|date',
						
						'dataFim' =>'nullable|date|after:dataInicio',
						'precoIndividual' => 'integer|min:0|nullable',
						'dataPagamento' =>'nullable|date|before_or_equal:dataFim|required_with:precoIndividual',
						// 'numeroInscricao'  =>'required|numeric',
						//  'ativado'  =>'required',
						// 'destaque' =>'required'

					];
				}
				# code...
			default:break;
		}

	}

	/**
	 * Get the validation messages that apply to the request.
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'nome.required'       => 'Nome é obrigatorio',
			'endereco.required'   => 'Endereço é obrigatorio',
			'dataInicio.required' => 'Data Inicio é obrigatorio',
			'dataFim' => 'Data Fim deve ser depois da data inicio',
			'dataPagamento' => 'Limite pagamento deve ser antes da date fim',
			
			// 'numeroInscricao.required' => 'Número Inscrição é obrigatorio',

		];
	}
}
