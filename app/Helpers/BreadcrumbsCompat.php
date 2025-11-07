<?php

namespace App\Helpers;

/**
 * Compatibility wrapper for old Creitive Breadcrumbs package
 * This allows the old Breadcrumbs::addCrumb() syntax to work without errors
 */
class BreadcrumbsCompat
{
    protected $crumbs = [];
    protected $divider = '/';
    protected $cssClasses = '';

    /**
     * Add a breadcrumb
     */
    public function addCrumb($title, $url = '')
    {
        $this->crumbs[] = [
            'title' => $title,
            'url' => $url
        ];
        return $this;
    }

    /**
     * Set divider
     */
    public function setDivider($divider)
    {
        $this->divider = $divider;
        return $this;
    }

    /**
     * Set CSS classes
     */
    public function setCssClasses($classes)
    {
        $this->cssClasses = $classes;
        return $this;
    }

    /**
     * Render breadcrumbs
     */
    public function render()
    {
        if (empty($this->crumbs)) {
            return '';
        }

        $html = '<ol class="' . $this->cssClasses . '">';
        
        foreach ($this->crumbs as $index => $crumb) {
            $isLast = ($index === count($this->crumbs) - 1);
            
            $html .= '<li>';
            
            if (!$isLast && !empty($crumb['url'])) {
                $html .= '<a href="' . $crumb['url'] . '">' . $crumb['title'] . '</a>';
            } else {
                $html .= $crumb['title'];
            }
            
            if (!$isLast && $this->divider) {
                $html .= ' <span class="divider">' . $this->divider . '</span> ';
            }
            
            $html .= '</li>';
        }
        
        $html .= '</ol>';
        
        return $html;
    }

    /**
     * Convert to string
     */
    public function __toString()
    {
        return $this->render();
    }
}
