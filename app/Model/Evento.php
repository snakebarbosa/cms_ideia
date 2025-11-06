<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Evento extends Model 
{
	use LogsActivity;

	protected static $logAttributes = ['*'];

	protected static $logName = 'Evento';

	protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

	public function tags() {
		return $this->belongsToMany('App\Model\Tag', 'tag_eventos', 'idEvento', 'idTag');
	}
	public function categorias() {
		return $this->belongsTo('App\Model\Categoria', 'idCategoria');
	}

	public function imagems() {
		return $this->belongsTo('App\Model\Imagem', 'idImagem');
	}

	public function conteudos() {
		return $this->hasMany('App\Model\Conteudo', 'idEvento');
	}

	public function user() {
		return $this->belongsTo('App\User', 'idUser');
	}

	public function contador() {
		return $this->hasMany('App\Model\Contador', 'idEvento');
	}

}
