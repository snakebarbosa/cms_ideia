<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\HasConteudos;

class Item extends Model 
{
	use LogsActivity;
	use HasConteudos;

	protected static $logAttributes = ['*'];

	protected static $logName = 'Item';

	protected static $logOnlyDirty = true;

   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

	public function tipos() {
		return $this->belongsTo('App\Model\Tipo', 'idTipo');
	}

	public function imagems() {
		return $this->belongsTo('App\Model\Imagem', 'idImagem');
	}

	public function tags() {
		return $this->belongsToMany('App\Model\Tag', 'tag_items', 'idItem', 'idTag');
	}

	/**
	 * Construi o menu para o frontend
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public static function tree($idTipo) {
		$subtree2 = null;
		$subtree1 = null;

		$catRaiz = DB::table('items')->where('idTipo', $idTipo)->where('default', 1)->get();

		$tree = array(
			"id"        => $catRaiz[0]->id,
			"titulo"    => $catRaiz[0]->titulo,
			'childreen' => Array()
		);

		$catChildren = Item::where('idTipo', $idTipo)->where('ativado', 1)->where('default', 0)->where('parent', $catRaiz[0]->id)->orderBy('order', 'ASC')->get();

		if (isset($catChildren)) {
			foreach ($catChildren as $value) {
				$value->conteudos;
				$catSubChildren = Item::where('idTipo', $idTipo)->where('ativado', 1)->where('default', 0)->where('parent', $value->id)->orderBy('order', 'ASC')->get();

				$conteudos1 = $value->conteudos;
				$imagem1    = $value->imagems;

				foreach ($conteudos1 as $conteudo) {
					$languages = $conteudo->languages;

				}

				if (isset($catSubChildren)) {
					$subtree2 = Array();

					foreach ($catSubChildren as $value2) {
						$conteudos = $value2->conteudos;

						foreach ($conteudos as $conteudo) {
							$languages = $conteudo->languages;

						}

						$value2['leaf'] = 1;
						$subtree2[]     = $value2;

					}//.Foreach
				}//.if

				$value['childreen'] = $subtree2;
				$subtree1[]         = $value;

			}
		}

		$tree['childreen'] = $subtree1;

		return $tree;

	}
}
