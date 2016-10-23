<?php

/**
 * Filter.php
 */

namespace WordPress2Drupal\Filter;

use WordPress2Drupal\Exception\RuntimeException;

/**
 * Class Filter
 * @package WordPress2Drupal\Filter
 */
class FilterFactory
{
    /**
     * @param $processor
     * @param array $options
     * @return mixed
     */
    static function load($filter, $options = array())
    {
        $class = $filter;
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        return new $class($options);
    }
}
