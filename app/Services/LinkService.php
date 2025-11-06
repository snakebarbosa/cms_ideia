<?php

namespace App\Services;

use App\Model\Link;
use App\Model\Contador;

class LinkService
{
    /**
     * Get all active links
     */
    public function getActiveLinks()
    {
        return Link::where('ativado', 1)->get();
    }

    /**
     * Get links by tag
     */
    public function getLinksByTag($tagId)
    {
        return Link::whereHas('tags', function($query) use ($tagId) {
                $query->where('tags.id', $tagId);
            })
            ->where('ativado', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Increment link click count
     * Uses both legacy and new polymorphic approach for compatibility
     */
    public function incrementClicks($linkId)
    {
        $link = Link::find($linkId);
        
        if ($link) {
            // New polymorphic approach
            Contador::create([
                'countable_type' => 'App\Model\Link',
                'countable_id' => $linkId,
                'action_type' => 'click',
                'idLink' => $linkId,  // Legacy column for backward compatibility
                'ip' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Get link click count
     * Supports both legacy and polymorphic counts
     */
    public function getClickCount($linkId)
    {
        // Count using polymorphic relationship
        $polymorphicCount = Contador::where('countable_type', 'App\Model\Link')
            ->where('countable_id', $linkId)
            ->where('action_type', 'click')
            ->count();

        // Count using legacy column (for old data)
        $legacyCount = Contador::where('idLink', $linkId)
            ->whereNull('countable_type')
            ->count();

        return $polymorphicCount + $legacyCount;
    }
}
