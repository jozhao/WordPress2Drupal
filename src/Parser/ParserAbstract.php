<?php

/**
 * @file ParserAbstract.php
 */

namespace WordPress2Drupal\Parser;

use WordPress2Drupal\Document\Document;

/**
 * Class ParserAbstract
 * @package WordPress2Drupal\Parser
 */
class ParserAbstract implements ParserInterface
{
    /**
     * @var null
     */
    private $_document;

    /**
     * @var array
     */
    private $_options;

    /**
     * @var
     */
    private $_items;

    /**
     * ParserAbstract constructor.
     * @param null $document
     * @param array $options
     */
    public function __construct(Document $document = null, $options = array())
    {
        $this->_document = $this->setDocument($document);
        $this->_options = $this->setOptions($options);
    }

    /**
     * @param Document $document
     */
    public function setDocument(Document $document)
    {
        $this->_document = $document;
        if (!empty($document)) {
            $this->setItems();
        }
    }

    /**
     * @return null
     */
    public function getDocument()
    {
        return $this->_document;
    }

    /**
     * @param $document
     */
    public function saveDocument(Document $document)
    {
        $this->_document->save($document);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->_options = $options;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @param mixed $items
     */
    public function setItems()
    {
        $this->_items = $this->_document->top('item');
    }

    /**
     * Parse function.
     */
    public function parse()
    {
        // TODO: Implement parse() method.
    }
}
