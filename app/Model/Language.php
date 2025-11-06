<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Language extends Model
{
	use LogsActivity;

	protected static $logAttributes = ['*'];

	protected static $logName = 'Language';

	protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
	
    public function conteudos(){
		return $this->hasMany('App\Model\Conteudo');
	}

	 
}
