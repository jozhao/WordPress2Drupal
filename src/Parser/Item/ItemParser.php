<?php

/**
 * @file ItemParser.php
 */

namespace WordPress2Drupal\Parser\Item;

/**
 * Class ItemParser
 * @package WordPress2Drupal\Parser\Item
 */
class ItemParser
{
    /**
     * @var
     */
    private $_item;

    private $_filters = [];

    /**
     * ItemParser constructor.
     * @param null $item
     */
    public function __construct($item = null, $filters = [])
    {
        $this->setItem($item);
        $this->setFilters($filters);
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->_item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item)
    {
        $this->_item = $item;
    }

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * @param mixed $filters
     */
    public function setFilters($filters)
    {
        $this->_filters = $filters;
    }


}
