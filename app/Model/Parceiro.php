<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Parceiro extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logName = 'Parceiro';

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    protected static $logOnlyDirty = true;
    public function imagems(){
   	  return $this->belongsTo('App\Model\Imagem','idImagem');
   }
}
