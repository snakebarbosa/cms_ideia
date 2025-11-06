<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Conteudo extends Model
{
  use LogsActivity;

  protected static $logAttributes = ['*'];

  protected static $logName = 'Conteudo';

  protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
  
  public function artigos(){
    return $this->belongsTo('App\Model\Artigo','idArtigo');
  }

  public function documentos(){
   	return $this->belongsTo('App\Model\Documento','idDocumento');
  }

  public function languages(){
	  return $this->belongsTo('App\Model\Language','idLanguage');
	}

	public function items(){
	 return $this->belongsTo('App\Model\Item','idItem');
	}
  
  public function slides(){
    return $this->belongsTo('App\Model\Slide','idSlide');
  }
  
  public function faqs(){
    return $this->belongsTo('App\Model\Faq','idFaq');
  }

  public function Categorias(){
    return $this->belongsTo('App\Model\Categoria','idCategoria');
  }

}
