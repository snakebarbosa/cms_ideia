<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasConteudos;

class Categoria extends Model 
{
	use LogsActivity;
	use HasConteudos;

	protected $fillable = [
		'categoria_tipo',
		'parent',
		'titulo',
		'slug',
		'ativado',
		'order',
		'default',
		'idTipo'
	];

	protected static $logAttributes = ['*'];

	protected static $logName = 'Categoria';

	protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
	
	public function documentos() {
		return $this->hasMany('App\Model\Documentos', 'idCategorias');
	}

	public function tags() {
		return $this->belongsToMany('App\Model\Tag', 'tag_categorias', 'idCategoria', 'idTag');
	}

	public function artigos() {
		return $this->hasMany('App\Model\Artigos', 'idCategorias');
	}
	public function faqs() {
		return $this->hasMany('App\Model\Faq', 'idCategorias');
	}

	public function links() {
		return $this->hasMany('App\Model\Link', 'idCategorias');
	}

	/**
	 *
	 */

	public static function tree($type) {
		$subtree2 = null;
		$subtree1 = null;

		$catRaiz = DB::table('categorias')->where('categoria_tipo', $type)->where('default', 1)->get();

		$tree = array(
			"id"        => $catRaiz[0]->id,
			"titulo"    => $catRaiz[0]->titulo,
			'childreen' => Array()
		);

		$catChildren = DB::table('categorias')->where('categoria_tipo', $type)->where('default', 0)->where('parent', $catRaiz[0]->id)->get();

		if (isset($catChildren)) {
			foreach ($catChildren as $value) {

				$catSubChildren = DB::table('categorias')->where('categoria_tipo', $type)->where('default', 0)->where('parent', $value->id)->get();

				if (isset($catSubChildren)) {
					$subtree2 = Array();

					foreach ($catSubChildren as $value2) {

						$subtree2[] = array(
							"id"      => $value2->id,
							"titulo"  => $value2->titulo,
							'ativado' => $value2->ativado,
							'leaf'    => 1,
						);

					}
				}

				$subtree1[] = array(
					"id"        => $value->id,
					"titulo"    => $value->titulo,
					'childreen' => $subtree2,
					'ativado'   => $value->ativado,
				);
			}
		}

		$tree['childreen'] = $subtree1;

		return $tree;

	}
}
