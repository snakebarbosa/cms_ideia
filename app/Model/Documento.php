<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Laravel\Scout\Searchable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Documento extends Model {

	// use Searchable;
	use LogsActivity;

	protected static $logAttributes = ['*'];

	protected static $logName = 'Documento';

	protected static $logOnlyDirty = true;



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

	protected $dates = ['publicar', 'despublicar', 'data_criacao'];
	
	public function tags() {
		return $this->belongsToMany('App\Model\Tag', 'tag_doc', 'idDoc', 'idTag');
	}

	public function categorias() {
		return $this->belongsTo('App\Model\Categoria', 'idCategoria');
	}

	public function tipos() {
		return $this->belongsTo('App\Model\Tipo', 'idTipo');
	}

	public function user() {
		return $this->belongsTo('App\User', 'idUser');
	}

	public function conteudos() {
		return $this->hasMany('App\Model\Conteudo', 'idDocumento');
	}

	/**
	 * Legacy relationship - kept for backward compatibility
	 */
	public function contador() {
		return $this->hasMany('App\Model\Contador', 'idDocumento');
	}

	/**
	 * Polymorphic relationship - new flexible approach
	 */
	public function contadores() {
		return $this->morphMany('App\Model\Contador', 'countable');
	}

	public function anexado() {
		return $this->belongsToMany('App\Model\Artigo', 'art_docs', 'idDocumento', 'idArtigo');
	}

	/**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'documentos_index';
    }

	// public static function scopeSearch($query, $s) {
	// 	return $query->where('nome', 'like', '%'.$s.'%');
	// 	// ->orWhere('description','like','%'.$s.'%');
	// }
}
