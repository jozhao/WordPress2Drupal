<?php

/**
 * @file ParserInterface.php
 */

namespace WordPress2Drupal\Parser;

use WordPress2Drupal\Document\Document;

/**
 * Interface ParserInterface
 * @package WordPress2Drupal\Parser
 */
interface ParserInterface
{
    /**
     * @param Document $document
     * @return mixed
     */
    public function setDocument(Document $document);

    /**
     * @return mixed
     */
    public function getDocument();

    /**
     * @param $document
     * @return mixed
     */
    public function saveDocument(Document $document);

    /**
     * @return mixed
     */
    public function parse();
}
