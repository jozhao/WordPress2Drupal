<?php

/**
 * @file DocumentInterface.php
 */

namespace WordPress2Drupal\Document;

/**
 * Interface DocumentInterface
 * @package WordPress2Drupal\Document
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
