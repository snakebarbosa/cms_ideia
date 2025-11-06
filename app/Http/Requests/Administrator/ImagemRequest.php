<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;

class ImagemRequest extends FormRequest {
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

		return [

			'titulo'    => 'required|max:255|',
			'file'      => 'required|image|mimes:jpeg,svg,png,jpg|max:2048',
			'categoria' => 'required',
		];
	}

	/**
	 * Get the validation messages that apply to the request.
	 *
	 * @return array
	 */
	public function messages() {
		return [
			// 'titulo.required' => 'Titulo da Imagem deve ser diferente',
			'file.required'      => 'Escolher ficheiro é obrigatorio, e deve ser do tipo(jpeg,png ou jpg)',
			'categoria.required' => 'Categoria é obrigatorio',

		];
	}
}
