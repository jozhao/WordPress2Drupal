<?php

/**
 * @file ItemFilter.php
 */

namespace WordPress2Drupal\Filter;

/**
 * Class ItemFilter
 * @package WordPress2Drupal\Filter
 */
class ItemFilter extends FilterAbstract
{
    /**
     * @var
     */
    protected $item;

    /**
     * ItemFilter constructor.
     * @param null $item
     */
    public function __construct($item = null)
    {
        if (!empty($item)) {
            $this->setItem($item);
        }
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }
}
