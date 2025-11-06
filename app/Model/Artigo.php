<?php

namespace App\Model;

use App\Model\Documento;
use App\Model\Faq;
use App\Model\Categoria;
// use Laravel\Scout\Searchable;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Artigo extends Model {

	// use Searchable;
	use LogsActivity;



	protected static $logAttributes = ['user.name', '*'];

	protected static $logName = 'Artigo';

	protected static $logOnlyDirty = true;

	protected $dates = ['publicar', 'despublicar', 'data_criacao'];

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
	
	public function tags() {
		return $this->belongsToMany('App\Model\Tag', 'tag_art', 'idArtigo', 'idTag');
	}

	public function anexos() {
		return $this->belongsToMany('App\Model\Documento', 'art_docs', 'idArtigo', 'idDocumento');
	}

	public function categorias() {
		return $this->belongsTo('App\Model\Categoria', 'idCategoria');
	}

	public function imagems() {
		return $this->belongsTo('App\Model\Imagem', 'idImagem');
	}

	public function user() {
		return $this->belongsTo('App\User', 'idUser');
	}

	public function conteudos() {
		return $this->hasMany('App\Model\Conteudo', 'idArtigo');
	}

	/**
	 * Legacy relationship - kept for backward compatibility
	 */
	public function contador() {
		return $this->hasMany('App\Model\Contador', 'idArtigo');
	}

	/**
	 * Polymorphic relationship - new flexible approach
	 */
	public function contadores() {
		return $this->morphMany('App\Model\Contador', 'countable');
	}

	public function scopeSearch($query, $s) {
		return $query->where('alias', 'like', '%'.$s.'%');
		// ->orWhere('description','like','%'.$s.'%');
	}
	/**
	 * 
	 */
	public static function isJson($string) {

	 	json_decode($string);

	 	return (json_last_error() == JSON_ERROR_NONE);
	}
	
	/**
	 * 
	 */
	public static function createSlug($title, $id = 0,$obj){
		
		$f = -1;
		 // Normalize the title
        $slug = str_slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = self::getRelatedSlugs($slug, $id,$obj);

		
        // If we haven't used it before then we are all good.
       // if (!$allSlugs->contains('slug',$slug)){
		foreach ($allSlugs as $key => $value) {
			
			if (self::isJson($value->slug)) {
				$slug_json = json_decode($value->slug);
			
				if ($slug_json->pt == $slug || $slug_json->en == $slug){
						$f = 1;
		        }
			}else{
				if ($allSlugs->contains('slug',$slug)){
						$f = 1;
				}
			}
		}
		

		if($f == -1){
		 	return $slug;
		}
		

        // Just append numbers like a savage until we find not used.
	   

        for ($i = 1; $i <= 10; $i++) {
			$f = -1;
            $newSlug = $slug.'-'.$i;

			foreach ($allSlugs as $key => $value) {
				if (self::isJson($value->slug)) {
						$slug_json = json_decode($value->slug);
					
						if ($slug_json->pt == $newSlug || $slug_json->en == $newSlug){
								$f = 1;
						}
					}else{
						if ($allSlugs->contains('slug',$slug)){
								$f = 1;
						}
					}
			}

			if($f == -1){
				return $newSlug;
			}
			
        }
		

        throw new \Exception('Can not create a unique slug');
	}
	/**
	 * 
	 */
    public static function getRelatedSlugs($slug, $id = 0,$obj)
    {

        return $obj::select('slug')->where('slug', 'like', '%'.$slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

}
