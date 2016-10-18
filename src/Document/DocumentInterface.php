<?php

/**
 * @file DocumentInterface.php
 */

namespace Wordpress2Drupal\Document;

/**
 * Interface DocumentInterface
 * @package Wordpress2Drupal\Document
 */
interface DocumentInterface
{
    /**
     * Add document.
     *
     * @return mixed
     */
    public function add($source, $options = array());

    /**
     * Load document.
     *
     * @return mixed
     */
    public function load();

    /**
     * @return mixed
     */
    public function save($document);
}
