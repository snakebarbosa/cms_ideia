<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Link extends Model
{
    use LogsActivity;

    protected $fillable = [
        'titulo', 'alias', 'url', 'idCategoria', 'ativado', 'order', 'destaque', 'updated_at'
    ];

    protected static $logAttributes = ['*'];

    protected static $logName = 'Link';

    protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function tags(){
   	    return $this->belongsToMany('App\Model\Tag','tag_links','idLink','idTag');
    }
    public function categorias(){
   	    return $this->belongsTo('App\Model\Categoria','idCategoria');
    }

    /**
     * Legacy relationship - kept for backward compatibility
     */
    public function contador(){
        return $this->hasMany('App\Model\Contador','idLink');
    }

    /**
     * Polymorphic relationship - new flexible approach
     */
    public function contadores(){
        return $this->morphMany('App\Model\Contador', 'countable');
    }
}
