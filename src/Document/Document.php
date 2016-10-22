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
        'post' => [
            'slug' => 'post',
            'total' => 0,
            'taxonomy' => '',
            'fields' => '',
        ],
        'page' => [
            'slug' => 'page',
            'total' => 0,
            'taxonomy' => '',
            'fields' => '',
        ],
        'attachment' => [
            'slug' => 'attachment',
            'total' => 0,
            'taxonomy' => '',
            'fields' => '',
        ],
        'nav_menu_item' => [
            'slug' => 'nav_menu_item',
            'total' => 0,
            'taxonomy' => '',
            'fields' => '',
        ],
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
    public function addBundle($bundle, $extras = [])
    {
        if (!isset($this->bundles[$bundle])) {
            $this->bundles[$bundle] = [
                'slug' => $bundle,
                'total' => 0,
                'taxonomy' => '',
                'fields' => '',
            ];
        }
        $this->bundles[$bundle]['slug'] = $bundle;
        $this->bundles[$bundle]['total'] += 1;

        // Taxonomy.
        if (isset($extras['taxonomy'])) {
            $taxonomy = [];
            if (!empty($this->bundles[$bundle]['taxonomy'])) {
                $taxonomy = explode(',', $this->bundles[$bundle]['taxonomy']);
            }
            $taxonomy += $extras['taxonomy'];
            $this->bundles[$bundle]['taxonomy'] = implode(',', array_unique($taxonomy));
        }

        // Other fields.
        if (isset($extras['fields'])) {
            $fields = [];
            if (!empty($this->bundles[$bundle]['fields'])) {
                $fields = explode(',', $this->bundles[$bundle]['fields']);
            }
            $fields += $extras['fields'];
            $this->bundles[$bundle]['fields'] = implode(',', array_unique($fields));
        }
    }
}
