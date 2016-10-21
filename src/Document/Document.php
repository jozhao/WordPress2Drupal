<?php

/**
 * @file Document.php
 */

namespace WordPress2Drupal\Document;

/**
 * Class Document
 * @package WordPress2Drupal\Document
 */
class Document extends DocumentAbstract
{
    protected $site;

    protected $items;

    protected $bundles = [
        'post' => [],
        'page' => [],
        'attachment' => [],
        'nav_menu_item' => [],
    ];

    /**
     * Document constructor.
     * @param null $source
     * @param array $options
     */
    public function __construct($source, array $options = [])
    {
        parent::__construct($source, $options);

        $this->setSite();
        $this->setItems();
    }

    /**
     * @return mixed
     */
    public function site()
    {
        return $this->getSite();
    }

    /**
     * @return mixed
     */
    protected function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    protected function setSite()
    {
        $site = [
            'title' => $this->document->xpath('/rss/channel/title')->text(),
            'link' => $this->document->xpath('/rss/channel/link')->text(),
            'language' => $this->document->xpath('/rss/channel/language')->text(),
            'pubDate' => $this->document->xpath('/rss/channel/pubDate')->text(),
            'description' => $this->document->xpath('/rss/channel/description')->text(),
        ];
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function items()
    {
        return self::getItems();
    }

    /**
     * @return mixed
     */
    protected function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    protected function setItems()
    {
        $this->items = $this->document->top('item');
    }

    /**
     * Get WordPress Post Type.
     */
    public function bundles()
    {
        return self::getBundles();
    }

    /**
     * @return mixed
     */
    protected function getBundles()
    {
        return $this->bundles;
    }

    /**
     * @param mixed $bundles
     */
    protected function setBundles($bundles)
    {
        $this->bundles = $bundles;
    }

    /**
     * @param $bundle
     */
    public function addBundle($bundle)
    {
        if (!isset($this->bundles[$bundle])) {
            $this->bundles[$bundle] = [];
        }
        $this->bundles[$bundle]['slug'] = $bundle;
        $this->bundles[$bundle]['total'] += 1;
    }
}
