<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Imagem extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logName = 'Imagem';

    protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
    
    public function imagems(){
   	  return $this->hasMany('App\Model\Slide','idSlide');
    }
}
