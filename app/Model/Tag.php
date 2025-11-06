<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tag extends Model 
{
	use LogsActivity;

	protected static $logAttributes = ['*'];

	protected static $logName = 'Tag';

	protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
	
	public function documentos() {
		return $this->belongsToMany('App\Model\Documento', 'tag_doc', 'idTag', 'idDoc');
	}

	public function artigos() {
		return $this->belongsToMany('App\Model\Artigo', 'tag_art', 'idTag', 'idArtigo');
	}
	public function faqs() {
		return $this->belongsToMany('App\Model\Faq', 'tag_faqs', 'idTag', 'idFaq');
	}
	public function links() {
		return $this->belongsToMany('App\Model\Link', 'tag_links', 'idTag', 'idLink');
	}

	public function categorias() {
		return $this->belongsToMany('App\Model\Categoria', 'tag_categorias', 'idTag', 'idCategoria');
	}
	public function tags() {
		return $this->belongsToMany('App\Model\Tag', 'tag_rels', 'idTag', 'idTag2');
	}

	public function items() {
		return $this->belongsToMany('App\Model\Tag', 'tag_items', 'idTag', 'idItem');
	}

	public function user() {
		return $this->belongsTo('App\User', 'idUser');
	}
	// public function tag() {
	//   return $this->belongsToMany('App\Tag', 'tag_rels', 'idTag2', 'idTag');
	// }

}
