<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Faq extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logName = 'Faq';

    protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function tags(){
        return $this->belongsToMany('App\Model\Tag','tag_faqs','idFaq','idTag');
    }

   	public function categorias(){
        return $this->belongsTo('App\Model\Categoria','idCategoria');
    }

    public function imagems(){
   	    return $this->belongsTo('App\Model\Imagem','idImagem');
    }

    public function conteudos(){
   	    return $this->hasMany('App\Model\Conteudo','idFaq');
    }

    /**
     * Legacy relationship - kept for backward compatibility
     */
    public function contador(){
        return $this->hasMany('App\Model\Contador','idFaq');
    }

    /**
     * Polymorphic relationship - new flexible approach
     */
    public function contadores(){
        return $this->morphMany('App\Model\Contador', 'countable');
    }
   
}
