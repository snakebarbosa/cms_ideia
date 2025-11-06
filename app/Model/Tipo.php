<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tipo extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logName = 'Tipo';

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
    
    public function items(){
   	 return $this->hasMany('App\Model\Item','idTipo');
    }

    public function tipos(){
   	 return $this->hasMany('App\Model\Documento','idTipo');
    }
}
