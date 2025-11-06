<?php

namespace App\Services;

use App\Model\Contador;
use Illuminate\Database\Eloquent\Model;

class TrackingService
{
    /**
     * Track any model access with action type
     * 
     * @param Model $model The model instance to track
     * @param string $actionType The action type: 'view', 'download', 'click', etc.
     * @param string|null $ip Optional IP address (defaults to request IP)
     * @return Contador
     */
    public function track(Model $model, string $actionType = 'view', ?string $ip = null)
    {
        return Contador::create([
            'countable_type' => get_class($model),
            'countable_id' => $model->id,
            'action_type' => $actionType,
            'ip' => $ip ?? request()->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Get count for a specific model and action
     * 
     * @param Model $model
     * @param string|null $actionType Optional filter by action type
     * @return int
     */
    public function getCount(Model $model, ?string $actionType = null)
    {
        $query = Contador::where('countable_type', get_class($model))
            ->where('countable_id', $model->id);

        if ($actionType) {
            $query->where('action_type', $actionType);
        }

        return $query->count();
    }

    /**
     * Get unique visitor count (by IP)
     * 
     * @param Model $model
     * @param string|null $actionType Optional filter by action type
     * @return int
     */
    public function getUniqueCount(Model $model, ?string $actionType = null)
    {
        $query = Contador::where('countable_type', get_class($model))
            ->where('countable_id', $model->id);

        if ($actionType) {
            $query->where('action_type', $actionType);
        }

        return $query->distinct('ip')->count('ip');
    }

    /**
     * Get counts grouped by action type
     * 
     * @param Model $model
     * @return array
     */
    public function getCountsByAction(Model $model)
    {
        return Contador::where('countable_type', get_class($model))
            ->where('countable_id', $model->id)
            ->groupBy('action_type')
            ->selectRaw('action_type, count(*) as count')
            ->pluck('count', 'action_type')
            ->toArray();
    }

    /**
     * Get recent activity for a model
     * 
     * @param Model $model
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentActivity(Model $model, int $limit = 10)
    {
        return Contador::where('countable_type', get_class($model))
            ->where('countable_id', $model->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Track article view (backward compatible)
     */
    public function trackArticleView($articleId)
    {
        $article = \App\Model\Artigo::find($articleId);
        if ($article) {
            return $this->track($article, 'view');
        }
        return null;
    }

    /**
     * Track document download (backward compatible)
     */
    public function trackDocumentDownload($documentId)
    {
        $document = \App\Model\Documento::find($documentId);
        if ($document) {
            return $this->track($document, 'download');
        }
        return null;
    }

    /**
     * Track FAQ view (backward compatible)
     */
    public function trackFaqView($faqId)
    {
        $faq = \App\Model\Faq::find($faqId);
        if ($faq) {
            return $this->track($faq, 'view');
        }
        return null;
    }

    /**
     * Track link click (backward compatible)
     */
    public function trackLinkClick($linkId)
    {
        $link = \App\Model\Link::find($linkId);
        if ($link) {
            return $this->track($link, 'click');
        }
        return null;
    }
}
