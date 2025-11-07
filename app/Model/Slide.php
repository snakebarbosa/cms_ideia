<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasConteudos;

class Slide extends Model
{
   use LogsActivity;
   use HasConteudos;

   protected $fillable = [
      'alias', 'url', 'idImagem', 'ativado', 'order', 'publicar', 'despublicar', 'updated_at'
   ];

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
}
