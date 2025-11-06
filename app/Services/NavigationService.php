<?php

namespace App\Services;

use App\Model\Categoria;
use App\Model\Tag;
use App\Model\Artigo;
use App\Model\Documento;
use App\Model\Faq;
use Breadcrumbs;
use URL;

class NavigationService
{
    /**
     * Generate breadcrumbs for different content types
     */
    public function generateBreadcrumbs($type, $id = 0, $obj = 0)
    {
        $breadcrumbs = Breadcrumbs::addCrumb('Home', URL::to('/'))->setDivider(' ')->setCssClasses('breadcrumb');

        switch ($type) {
            case 'navArtigo':
                $cat = Categoria::find($id);
                if ($cat && $cat->idParent != 0) {
                    $catP = Categoria::find($cat->idParent);
                    if ($catP) {
                        $breadcrumbs->addCrumb($catP->titulo, URL::to('/navartigo/' . $catP->id));
                    }
                }
                if ($cat) {
                    $breadcrumbs->addCrumb($cat->titulo, URL::to('/navartigo/' . $cat->id));
                }
                break;

            case 'navDoc':
                $cat = Categoria::find($id);
                if ($cat && $cat->idParent != 0) {
                    $catP = Categoria::find($cat->idParent);
                    if ($catP) {
                        $breadcrumbs->addCrumb($catP->titulo, URL::to('/navdoc/' . $catP->id));
                    }
                }
                if ($cat) {
                    $breadcrumbs->addCrumb($cat->titulo, URL::to('/navdoc/' . $cat->id));
                }
                break;

            case 'navFaq':
                $cat = Categoria::find($id);
                if ($cat && $cat->idParent != 0) {
                    $catP = Categoria::find($cat->idParent);
                    if ($catP) {
                        $breadcrumbs->addCrumb($catP->titulo, URL::to('/navfaq/' . $catP->id));
                    }
                }
                if ($cat) {
                    $breadcrumbs->addCrumb($cat->titulo, URL::to('/navfaq/' . $cat->id));
                }
                break;

            case 'artigo':
                $art = Artigo::find($id);
                if ($art) {
                    $cat = $art->categorias;
                    if ($cat && $cat->idParent != 0) {
                        $catP = Categoria::find($cat->idParent);
                        if ($catP) {
                            $breadcrumbs->addCrumb($catP->titulo, URL::to('/navartigo/' . $catP->id));
                        }
                    }
                    if ($cat) {
                        $breadcrumbs->addCrumb($cat->titulo, URL::to('/navartigo/' . $cat->id));
                    }
                }
                break;

            case 'documento':
                $doc = Documento::find($id);
                if ($doc) {
                    $cat = $doc->categorias;
                    if ($cat && $cat->idParent != 0) {
                        $catP = Categoria::find($cat->idParent);
                        if ($catP) {
                            $breadcrumbs->addCrumb($catP->titulo, URL::to('/navdoc/' . $catP->id));
                        }
                    }
                    if ($cat) {
                        $breadcrumbs->addCrumb($cat->titulo, URL::to('/navdoc/' . $cat->id));
                    }
                }
                break;

            case 'faq':
                $faq = Faq::find($id);
                if ($faq) {
                    $cat = $faq->categorias;
                    if ($cat && $cat->idParent != 0) {
                        $catP = Categoria::find($cat->idParent);
                        if ($catP) {
                            $breadcrumbs->addCrumb($catP->titulo, URL::to('/navfaq/' . $catP->id));
                        }
                    }
                    if ($cat) {
                        $breadcrumbs->addCrumb($cat->titulo, URL::to('/navfaq/' . $cat->id));
                    }
                }
                break;

            case 'navtag':
                $tag = Tag::find($id);
                if ($tag) {
                    $breadcrumbs->addCrumb($tag->name, URL::to('/navtag/' . $tag->id . '/' . $obj));
                }
                break;
        }

        return $breadcrumbs->render();
    }

    /**
     * Get navigation items by tag
     */
    public function getNavigationByTag($idTag, $type)
    {
        $tag = Tag::find($idTag);
        if (!$tag) {
            return collect();
        }

        switch ($type) {
            case 'artigo':
                return $tag->artigos()
                    ->where('ativado', 1)
                    ->where('publicar', '<=', now())
                    ->where('despublicar', '>=', now())
                    ->orderBy('created_at', 'desc')
                    ->get();

            case 'documento':
                return $tag->documentos()
                    ->where('ativado', 1)
                    ->where('publicar', '<=', now())
                    ->where('despublicar', '>=', now())
                    ->orderBy('created_at', 'desc')
                    ->get();

            case 'faq':
                return $tag->faqs()
                    ->where('ativado', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();

            default:
                return collect();
        }
    }

    /**
     * Get navigation items by category
     */
    public function getNavigationByCategory($idCategory, $type)
    {
        $category = Categoria::find($idCategory);
        if (!$category) {
            return collect();
        }

        switch ($type) {
            case 'artigo':
                return Artigo::where('idCategoria', $idCategory)
                    ->where('ativado', 1)
                    ->where('publicar', '<=', now())
                    ->where('despublicar', '>=', now())
                    ->orderBy('created_at', 'desc')
                    ->get();

            case 'documento':
                return Documento::where('idCategoria', $idCategory)
                    ->where('ativado', 1)
                    ->where('publicar', '<=', now())
                    ->where('despublicar', '>=', now())
                    ->orderBy('created_at', 'desc')
                    ->get();

            case 'faq':
                return Faq::where('idCategoria', $idCategory)
                    ->where('ativado', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();

            default:
                return collect();
        }
    }

    /**
     * Get category by slug
     */
    public function getCategoryBySlug($slug, $type)
    {
        $categories = Categoria::where('categoria_tipo', $type)->get();

        foreach ($categories as $category) {
            $slugData = json_decode($category->slug, true);
            
            if ($slugData) {
                foreach ($slugData as $key => $value) {
                    if ($value === $slug) {
                        return $category;
                    }
                }
            }
        }

        return null;
    }
}
