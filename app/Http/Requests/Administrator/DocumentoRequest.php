<?php

namespace App\Http\Requests\Administrator;

use App\Rules\DataValidacao;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class DocumentoRequest extends FormRequest {
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
						'tituloEN' => 'required|max:200',
						'textoen'  => 'required',
						'textopt'  => 'required',
						//  'ativado'     => 'required',
						'idCategoria' => 'required',
						// 'idTipo'      => 'required',
						//  'destaque'    => 'required',
						'filePT' => 'required|file|max:10240',
						'fileEN' => 'file|max:10240',
						'data_criacao' => 'date|before_or_equal:' . Carbon::now(),
						'publicar' => 'before:despublicar',
					];
				}

			case 'PUT':
			case 'PATCH':
				{
					// Get document ID from route - try both parameter names
					$documentId = $this->route('Documento') ?? $this->route('documento');

					return [
						'tituloPT' => 'required|max:200',
						'tituloEN' => 'required|max:200',
						'textoen'  => 'required',
						'textopt'  => 'required',
						//  'ativado'     => 'required',
						'idCategoria' => 'required',
						// 'idTipo'      => 'required',
						//  'destaque'    => 'required',
						'data_criacao' => 'date|before_or_equal:' . Carbon::now(),
						'publicar' => 'before:despublicar',
						'slug_pt' => [
							'nullable',
							'max:250',
							'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
							function ($attribute, $value, $fail) use ($documentId) {
								if (!empty($value) && $documentId) {
									// Get current document's slug to compare
									$currentDoc = \App\Model\Documento::find($documentId);
									if ($currentDoc) {
										$currentSlug = json_decode($currentDoc->slug ?? '{}', true);
										$currentSlugPT = $currentSlug['pt'] ?? null;
										
										// Only check uniqueness if slug is different from current
										if ($value !== $currentSlugPT) {
											$exists = \App\Model\Documento::where('id', '!=', $documentId)
												->where('slug', 'like', '%"pt":"' . $value . '"%')
												->exists();
											if ($exists) {
												$fail('O slug PT já está em uso por outro documento.');
											}
										}
									}
								}
							},
						],
						'slug_en' => [
							'nullable',
							'max:250',
							'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
							function ($attribute, $value, $fail) use ($documentId) {
								if (!empty($value) && $documentId) {
									// Get current document's slug to compare
									$currentDoc = \App\Model\Documento::find($documentId);
									if ($currentDoc) {
										$currentSlug = json_decode($currentDoc->slug ?? '{}', true);
										$currentSlugEN = $currentSlug['en'] ?? null;
										
										// Only check uniqueness if slug is different from current
										if ($value !== $currentSlugEN) {
											$exists = \App\Model\Documento::where('id', '!=', $documentId)
												->where('slug', 'like', '%"en":"' . $value . '"%')
												->exists();
											if ($exists) {
												$fail('O slug EN já está em uso por outro documento.');
											}
										}
									}
								}
							},
						],
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
			'tituloPT.required'    => 'Titulo PT é obrigatorio',
			'tituloEN.required'    => 'Titulo EN é obrigatorio',
			'textoen.required'     => 'Descrição (Inglês) é obrigatorio',
			'textopt.required'     => 'Descrição (Português) é obrigatorio',
			'idCategoria.required' => 'Escolher pasta é obrigatorio',
			'file.required'        => 'Escolher ficheiro é obrigatorio, e deve ser do tipo(pdf,docx,doc,xls e xlsx )',
			// 'idTipo.required' => 'Tipo é obrigatorio',
			'data_criacao.before_or_equal' => 'Data de criação tem que ser menor ou igual a hoje',
			'publicar.before' => 'Data de publicação tem que ser antes da data de remoção.',
			'slug_pt.max' => 'O slug PT não pode ter mais de 250 caracteres.',
			'slug_pt.regex' => 'O slug PT deve conter apenas letras minúsculas, números e hífens.',
			'slug_en.max' => 'O slug EN não pode ter mais de 250 caracteres.',
			'slug_en.regex' => 'O slug EN deve conter apenas letras minúsculas, números e hífens.',
		];
	}

}
