<?php

namespace App\Services;

use App\Model\Artigo;
use App\Model\Documento;
use App\Model\Faq;
use App\Model\Slide;
use App\Model\Link;
use App\Model\Parceiro;
use App\Model\Configuration;

class HomeService
{
    protected $configService;
    protected $artigoService;
    protected $documentoService;
    protected $faqService;

    public function __construct(
        ConfigurationService $configService,
        ArtigoPublicService $artigoService,
        DocumentoPublicService $documentoService,
        FaqService $faqService
    ) {
        $this->configService = $configService;
        $this->artigoService = $artigoService;
        $this->documentoService = $documentoService;
        $this->faqService = $faqService;
    }

    /**
     * Get all data for home page
     */
    public function getHomePageData()
    {
        return [
            'docs' => [],
            'dlast' => $this->documentoService->getLatestDocuments(5),
            'random' => [],
            'news' => $this->artigoService->getRecentNews(5),
            'allNews' => $this->artigoService->getAllNews(),
            'video' => $this->getVideo(),
            'info' => [],
            'faqs' => $this->faqService->getFeaturedFaqs(5),
            'tab1pos1' => Artigo::find($this->configService->getConfig('tab1_pos1')),
            'tab1pos2' => Artigo::find($this->configService->getConfig('tab1_pos2')),
            'tab1pos3' => Artigo::find($this->configService->getConfig('tab1_pos3')),
            'tab2pos1' => $this->getDocumentsByConfig('tab2_pos1'),
            'tab2pos2' => $this->getDocumentsByConfig('tab2_pos2'),
            'tab2pos3' => $this->getDocumentsByConfig('tab2_pos3'),
            'tab2pos4' => $this->artigoService->getRecentNews(5),
            'tab2pos5' => $this->getDocumentsByConfig('tab2_pos5'),
            'catnoticia' => $this->configService->getConfig('catnoticia'),
        ];
    }

    /**
     * Get documents by configuration category
     */
    protected function getDocumentsByConfig($configKey)
    {
        $categoryId = $this->configService->getConfig($configKey);
        
        if (!$categoryId) {
            return collect();
        }

        return Documento::where('idCategoria', $categoryId)
            ->where('ativado', 1)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get video configuration
     */
    protected function getVideo()
    {
        $config = Configuration::first();
        
        if (!$config) {
            return ['url' => null];
        }

        $json = json_decode($config->config, true);
        return ['url' => $json['video'] ?? null];
    }

    /**
     * Get slides for carousel
     */
    public function getSlides()
    {
        return Slide::where('ativado', 1)
            ->orderBy('order', 'asc')
            ->get();
    }

    /**
     * Get partners
     */
    public function getPartners()
    {
        return Parceiro::where('ativado', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get links
     */
    public function getLinks()
    {
        return Link::where('ativado', 1)
            ->orderBy('order', 'asc')
            ->get();
    }
}
