<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Conteudo extends Model
{
    use LogsActivity;

    protected $fillable = [
        'contentable_type',
        'contentable_id',
        'idLanguage',
        'titulo',
        'texto',
        'ativado',
    ];

    protected static $logAttributes = ['*'];
    protected static $logName = 'Conteudo';
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    /**
     * Get the owning contentable model (polymorphic relationship)
     */
    public function contentable()
    {
        return $this->morphTo();
    }

    /**
     * Get the language
     */
    public function languages()
    {
        return $this->belongsTo('App\Model\Language', 'idLanguage');
    }

    /**
     * Scope to filter by language
     */
    public function scopeLanguage($query, $languageId)
    {
        return $query->where('idLanguage', $languageId);
    }

    /**
     * Scope to filter by language tag
     */
    public function scopeLanguageTag($query, $tag)
    {
        return $query->whereHas('languages', function ($q) use ($tag) {
            $q->where('tag', $tag);
        });
    }

    /**
     * Get Portuguese content
     */
    public function scopePortuguese($query)
    {
        return $query->languageTag('pt');
    }

    /**
     * Get English content
     */
    public function scopeEnglish($query)
    {
        return $query->languageTag('en');
    }

    // DEPRECATED: Keep for backward compatibility during transition
    // These will be removed after full migration
    public function artigos()
    {
        return $this->belongsTo('App\Model\Artigo', 'idArtigo');
    }

    public function documentos()
    {
        return $this->belongsTo('App\Model\Documento', 'idDocumento');
    }

    public function items()
    {
        return $this->belongsTo('App\Model\Item', 'idItem');
    }

    public function slides()
    {
        return $this->belongsTo('App\Model\Slide', 'idSlide');
    }

    public function faqs()
    {
        return $this->belongsTo('App\Model\Faq', 'idFaq');
    }

    public function Categorias()
    {
        return $this->belongsTo('App\Model\Categoria', 'idCategoria');
    }
}
