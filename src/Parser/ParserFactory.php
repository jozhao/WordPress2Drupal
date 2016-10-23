<?php

/**
 * @file ParserFactory.php
 */

namespace WordPress2Drupal\Parser;

use WordPress2Drupal\Exception\RuntimeException;

/**
 * Class ParserFactory
 * @package WordPress2Drupal\Parser
 */
class ParserFactory
{
    /**
     * @param $parser
     * @param array $options
     * @return mixed
     */
    static function load($parser, $options = array())
    {
        $class = $parser;
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        return new $class($options);
    }
}
