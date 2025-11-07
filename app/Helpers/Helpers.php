<?php

namespace App\Helpers;

use App\Model\Conteudo;
use App\Model\Language;
use Purifier;
use Illuminate\Support\Str;
use App\Model\Parceiro;
use App\Model\Link;
use Carbon\Carbon;
use App\Model\Configuration;
use App\Model\Slide;
use App\Model\Item;

class Helpers
{
    
    /**
     * Save conteudos for any model using polymorphic relationship
     * 
     * @param object $request - Request object with tituloPT, textopt, tituloEN, textoen
     * @param Model $model - Any model that uses HasConteudos trait
     * @return void
     */
    public static function guardarConteudos($request, $model)
    {
        // Check if model uses HasConteudos trait
        if (method_exists($model, 'saveConteudos')) {
            $data = [
                'tituloPT' => $request->tituloPT ?? '',
                'textopt' => $request->textopt ?? '',
                'tituloEN' => $request->tituloEN ?? '',
                'textoen' => $request->textoen ?? '',
            ];
            
            $model->saveConteudos($data);
            return;
        }

        // Fallback to old method for backward compatibility
        $languages = Language::all();
    
        try {
            $conteudos = [];
            
            foreach ($languages as $language) {
                $conteudo = new Conteudo();
                
                if ($language->tag == 'pt') {
                    $conteudo->titulo = $request->tituloPT ?? '';
                    $conteudo->texto = $request->textopt ?? '';
                } elseif ($language->tag == 'en') {
                    $conteudo->titulo = $request->tituloEN ?? '';
                    $conteudo->texto = $request->textoen ?? '';
                }
                
                $conteudo->idLanguage = $language->id;
                $conteudos[] = $conteudo;
            }
            
            $model->conteudos()->saveMany($conteudos);
        } catch (\Throwable $th) {
            \Log::error('Error saving conteudos: ' . $th->getMessage());
        }
    }

    /**
     * Update conteudos for any model using polymorphic relationship
     * 
     * @param Collection $conteudos - Existing conteudos collection
     * @param object $request - Request object
     * @param Model $model - The parent model
     * @return void
     */
    public static function atualizarConteudo($conteudos, $request, $model)
    {
        // Check if model uses HasConteudos trait
        if (method_exists($model, 'saveConteudos')) {
            $data = [
                'tituloPT' => $request->tituloPT ?? '',
                'textopt' => $request->textopt ?? '',
                'tituloEN' => $request->tituloEN ?? '',
                'textoen' => $request->textoen ?? '',
            ];
            
            $model->saveConteudos($data);
            return;
        }

        // Fallback to old method
        foreach ($conteudos as $conteudo) {
            if ($conteudo->languages->tag == "pt") {
                $conteudo->titulo = $request->tituloPT ?? '';
                $conteudo->texto = $request->textopt ?? '';
            } else {
                $conteudo->titulo = $request->tituloEN ?? '';
                $conteudo->texto = $request->textoen ?? '';
            }
        }
        
        $model->conteudos()->saveMany($conteudos);
    }

    public static function criarSlug($request, $obj)
    {
        $slugPT = Str::slug($request->tituloPT . ' ' . $obj->id, '-');
        $slugEN = Str::slug($request->tituloEN . ' ' . $obj->id, '-');

        $arr_tojson = array(
            'pt' => $slugPT,
            'en' => $slugEN,
        );

        $slug = json_encode($arr_tojson);

        $obj::where('id', $obj->id)->update(['slug' => $slug]);
    }

    /**
     * Update slug with manual inputs while preserving auto-generated values for empty fields
     */
    public static function updateSlugWithManualInputs($request, $obj)
    {
        // Get existing slug data
        $existingSlugData = json_decode($obj->slug ?? '{}', true);
        
        // Prepare new slug data
        $newSlugData = [];
        
        // Handle PT slug
        if (isset($request->slug_pt) && !empty($request->slug_pt)) {
            $newSlugData['pt'] = $request->slug_pt;
        } else {
            // Auto-generate PT slug if not provided manually
            $newSlugData['pt'] = $existingSlugData['pt'] ?? Str::slug($request->tituloPT . ' ' . $obj->id, '-');
        }
        
        // Handle EN slug
        if (isset($request->slug_en) && !empty($request->slug_en)) {
            $newSlugData['en'] = $request->slug_en;
        } else {
            // Auto-generate EN slug if not provided manually
            $newSlugData['en'] = $existingSlugData['en'] ?? Str::slug($request->tituloEN . ' ' . $obj->id, '-');
        }

        $slug = json_encode($newSlugData);
        $obj::where('id', $obj->id)->update(['slug' => $slug]);
    }


    public static function salvarTags($request, $obj)
    {
        if (isset($request->tag)) {
            $obj->tags()->detach();
        }

        if (isset($request->tag)) {
            $obj->tags()->sync($request->tag, false);
        } else {
            $obj->tags()->sync(array());
        }
    }

    public static function getSlides()
    {
        $slides = Slide::where('posicao', 'topo')
                        ->where('ativado', '1')
                        ->where('publicar', '<=', Carbon::now())
                        ->where('despublicar', '>=', Carbon::now())
                        ->orderBy('order', 'asc')->get();
        // $path   = storage_path() . "/json/slideHome.json";
        // $slides = json_decode(file_get_contents($path), true);

        return $slides;
    }

    public static function getParceiros()
    {
        $parceiros = Parceiro::where('ativado', 1)->get();
        if (isset($parceiros) && $parceiros->count() != 0) {
            foreach ($parceiros as $parceiro) {
                $imagems = $parceiro->imagems;
            }
        }
        return $parceiros;
    }

    public static function getLinks()
    {
        $link = Link::where('ativado', 1)->get();
        return $link;
    }

    public static function getNavTag($id, $obj, $type)
    {
        $tags = [$id];
        if ($type != '0') {           
            $content =  $obj::whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('idTag', $tags);
            })->where('ativado', 1)->where($type, 1)->limit(6)->orderBy('created_at', 'desc')->get();
        } else {
            $content = $obj::whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('idTag', $tags);
            })->limit(7)->where('ativado', 1)->orderBy('created_at', 'desc')->get();
        }
        return $content;
    }

    public static function validarDatas($request)
    {
        $datePublicar = Carbon::parse($request->publicar);
        $dateDespublicar = Carbon::parse($request->despublicar);

        if ($datePublicar > $dateDespublicar) {
            return false;
        }
        return true;
    }

    public static function getconfig($type)
    {
        $config = Configuration::all()->first();
        $json = (array) json_decode($config->config);
        return $json[$type];
    }


    public static function getMenu($tipo)
    {
        $mp = self::getconfig($tipo);
        $menu = Item::tree($mp);
        return $menu;
    }

    /**
     * Calculate and format file size from document URL
     * 
     * @param string|object $url Document URL (can be JSON string or object with 'pt'/'en' properties)
     * @param string $lang Language code ('pt' or 'en'), defaults to 'pt'
     * @return string Formatted file size (e.g., "1.5 MB", "500 KB", "-")
     */
    public static function getDocumentFileSize($url, $lang = 'pt')
    {
        try {
            // Parse URL if it's a JSON string
            if (is_string($url)) {
                $url = json_decode($url);
            }

            // Check if requested language file path exists
            if (!isset($url->{$lang})) {
                return '-';
            }

            // Get file path
            $filePath = storage_path('documento/' . $url->{$lang});

            // Check if file exists
            if (!file_exists($filePath)) {
                return '-';
            }

            // Get file size in bytes
            $fileSize = filesize($filePath);

            // Format file size
            if ($fileSize < 1024) {
                return $fileSize . ' B';
            } elseif ($fileSize < 1048576) {
                return round($fileSize / 1024, 2) . ' KB';
            } elseif ($fileSize < 1073741824) {
                return round($fileSize / 1048576, 2) . ' MB';
            } else {
                return round($fileSize / 1073741824, 2) . ' GB';
            }
        } catch (\Exception $e) {
            return '-';
        }
    }
}
