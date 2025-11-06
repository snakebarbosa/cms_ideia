<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{
    protected $fillable = [
        'idDocumento',      // Legacy - kept for backward compatibility
        'idArtigo',         // Legacy - kept for backward compatibility
        'idFaq',            // Legacy - kept for backward compatibility
        'idLink',           // Legacy - kept for backward compatibility
        'countable_type',   // Polymorphic: Model class name (e.g., 'App\Model\Artigo')
        'countable_id',     // Polymorphic: Model ID
        'action_type',      // Action: 'view', 'download', 'click', etc.
        'ip',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the owning countable model (polymorphic relationship)
     */
    public function countable()
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by action type
     */
    public function scopeOfAction($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope to filter by model type
     */
    public function scopeOfType($query, $modelClass)
    {
        return $query->where('countable_type', $modelClass);
    }
}
