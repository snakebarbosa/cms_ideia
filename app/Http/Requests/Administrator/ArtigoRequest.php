<?php

namespace App\Http\Requests\Administrator;

use App\Rules\DataValidacao;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArtigoRequest extends FormRequest {
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
						'tituloPT' => 'required|max:200',
						'tituloEN' => 'nullable|max:200',
						'textoen'  => 'nullable',
						'textopt'  => 'required',
						'idCategoria' => 'required|exists:categorias,id',
						'keyword' => 'nullable|string|max:250',
						'tag' => 'nullable|array',
						'tag.*' => 'exists:tags,id',
						'idimagem' => 'nullable|numeric|exists:imagems,id',
						'iddocumentoartigo' => 'nullable|array',
						'data_criacao' => 'required|date|before_or_equal:' . Carbon::now(),
						'publicar' => 'required|date|before:despublicar',
						'despublicar' => 'nullable|date',
					];
				}

			case 'PUT':
			case 'PATCH':
				{
					$rules = [
						'tituloPT' => 'required|max:200',
						'tituloEN' => 'nullable|max:200',
						'textoen'  => 'nullable',
						'textopt'  => 'required',
						'idCategoria' => 'required|exists:categorias,id',
						'keyword' => 'nullable|string|max:250',
						'tag' => 'nullable|array',
						'tag.*' => 'exists:tags,id',
						'idimagem' => 'nullable|numeric|exists:imagems,id',
						'iddocumentoartigo' => 'nullable|array',
						'data_criacao' => 'required|date|before_or_equal:' . Carbon::now(),
						'publicar' => 'required|date|before:despublicar',
						'despublicar' => 'nullable|date',
					];

					// Add slug validation for updates (editing existing articles)
					if ($this->route('Artigo')) {
						$articleId = $this->route('Artigo');
						$rules['slug_pt'] = [
							'nullable',
							'string',
							'max:250',
							'regex:/^[a-z0-9\-]+$/', // Only lowercase letters, numbers, and hyphens
							function ($attribute, $value, $fail) use ($articleId) {
								if ($value) {
									$existingArticle = \App\Model\Artigo::where('id', '!=', $articleId)
										->whereRaw("JSON_EXTRACT(slug, '$.pt') = ?", [$value])
										->first();
									if ($existingArticle) {
										$fail('O slug PT já está sendo usado por outro artigo.');
									}
								}
							}
						];
						$rules['slug_en'] = [
							'nullable',
							'string',
							'max:250',
							'regex:/^[a-z0-9\-]+$/', // Only lowercase letters, numbers, and hyphens
							function ($attribute, $value, $fail) use ($articleId) {
								if ($value) {
									$existingArticle = \App\Model\Artigo::where('id', '!=', $articleId)
										->whereRaw("JSON_EXTRACT(slug, '$.en') = ?", [$value])
										->first();
									if ($existingArticle) {
										$fail('O slug EN já está sendo usado por outro artigo.');
									}
								}
							}
						];
					}

					return $rules;
				}
			default:
				break;
		}

	}

	/**
	 * Get the validation messages that apply to the request.
	 *
	 * @return array
	 */
	public function messages() {
		return [
			'tituloPT.required'    => 'Titulo PT é obrigatorio',
			'tituloEN.required'    => 'Titulo EN é obrigatorio',
			'textoen.required'     => 'Artigo (Inglês) é obrigatorio',
			'textopt.required'     => 'Artigo (Português) é obrigatorio',
			'idCategoria.required' => 'Categoria é obrigatorio',
			'idCategoria.exists' => 'A categoria selecionada não existe.',
			'idimagem.required'    => 'Imagem é obrigatorio',
			'idimagem.exists' => 'A imagem selecionada não existe.',
			'tag.*.exists' => 'Uma ou mais tags selecionadas não existem.',
			'data_criacao.required' => 'A data de criação é obrigatória.',
			'data_criacao.before_or_equal' => 'Data de criação tem que ser menor ou igual a hoje',
			'publicar.required' => 'A data de publicação é obrigatória.',
			'publicar.before' => 'Data de publicação tem que ser antes da data de remoção.',
			'publicar.date' => 'A data de publicação deve ser uma data válida.',
			'despublicar.date' => 'A data de remoção deve ser uma data válida.',
			'slug.regex' => 'O slug deve conter apenas letras minúsculas, números e hífens.',
			'slug.unique' => 'Este slug já está em uso. Por favor, escolha outro.',
		];
	}
}
