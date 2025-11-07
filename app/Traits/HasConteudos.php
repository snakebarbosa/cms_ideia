<?php

namespace App\Traits;

use App\Model\Conteudo;
use App\Model\Language;

trait HasConteudos
{
    /**
     * Get all conteudos for this model (polymorphic relationship)
     */
    public function conteudos()
    {
        return $this->morphMany(Conteudo::class, 'contentable');
    }

    /**
     * Get Portuguese content
     */
    public function conteudoPT()
    {
        return $this->conteudos()->languageTag('pt')->first();
    }

    /**
     * Get English content
     */
    public function conteudoEN()
    {
        return $this->conteudos()->languageTag('en')->first();
    }

    /**
     * Get content by language ID
     */
    public function getConteudoByLanguage($languageId)
    {
        return $this->conteudos()->where('idLanguage', $languageId)->first();
    }

    /**
     * Get content by language tag (pt, en, etc)
     */
    public function getConteudoByLanguageTag($tag)
    {
        return $this->conteudos()->languageTag($tag)->first();
    }

    /**
     * Create or update content for all languages
     * 
     * @param array $data - ['tituloPT' => '', 'textopt' => '', 'tituloEN' => '', 'textoen' => '']
     * @return void
     */
    public function saveConteudos(array $data)
    {
        $languages = Language::all();

        foreach ($languages as $language) {
            $conteudo = $this->conteudos()->where('idLanguage', $language->id)->first();

            if (!$conteudo) {
                $conteudo = new Conteudo();
                $conteudo->idLanguage = $language->id;
            }

            if ($language->tag == 'pt') {
                $conteudo->titulo = $data['tituloPT'] ?? '';
                $conteudo->texto = $data['textopt'] ?? '';
            } elseif ($language->tag == 'en') {
                $conteudo->titulo = $data['tituloEN'] ?? '';
                $conteudo->texto = $data['textoen'] ?? '';
            }

            $this->conteudos()->save($conteudo);
        }
    }

    /**
     * Delete all related conteudos
     */
    public function deleteConteudos()
    {
        return $this->conteudos()->delete();
    }

    /**
     * Boot the trait
     */
    protected static function bootHasConteudos()
    {
        // Automatically delete conteudos when model is deleted
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                $model->conteudos()->forceDelete();
            } else {
                $model->conteudos()->delete();
            }
        });
    }
}
