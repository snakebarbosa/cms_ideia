<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Slide extends Model
{
   use LogsActivity;

   protected static $logAttributes = ['*'];

   protected static $logName = 'Slide';

   protected static $logOnlyDirty = true;
   
   protected $dates = ['publicar', 'despublicar'];

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

   public function imagems(){
     return $this->belongsTo('App\Model\Imagem','idImagem');
   }
   public function conteudos(){
      return $this->hasMany('App\Model\Conteudo','idSlide');
   }
}
