<?php

/**
 * @file DocumentAbstract.php
 */

namespace Wordpress2Drupal\Document;

abstract class DocumentAbstract implements DocumentInterface
{
    protected $source;
    protected $document;
    protected $options = [
        'format_output' => 1,
    ];
    protected $errors = [];

    /**
     * DocumentAbstract constructor.
     *
     * @param $options
     */
    public function __construct($source = null, $options = array())
    {
        $this->add($source, $options);
    }

    /**
     * Add document into object.
     *
     * @param $source
     */
    public function add($source, $options = array())
    {
        $this->setSource($source);
        $this->setQp($source);
    }

    /**
     * Load document as an object.
     *
     * @return mixed
     */
    public function load()
    {
        return self::getQp();
    }

    /**
     * @param $document
     */
    public function save($document)
    {
        // Save document.
        $this->setQp($document);
    }

    /**
     * Save document as file.
     */
    public function saveFile()
    {
        try {
            // Create directory if not exist.
            if (!is_dir(DIRECTORY_DATA)) {
                @mkdir(DIRECTORY_DATA, 0755, true);
            }

            // Set directory permission.
            if (!is_writable(DIRECTORY_DATA)) {
                @chmod(DIRECTORY_DATA, 0755);
            }

            // Generate filename.
            $filename = DIRECTORY_DATA.DIRECTORY_SEPARATOR.'optimized-'.time().'-'.basename($this->getSource());

            // Write XML.
            $doc = $this->document->xml();
            // Remove blank lines.
            $doc = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\r\n", $doc);
            $this->save($doc);
            $this->document->writeXML($filename);
            // Update source.
            $this->setSource($filename);
        } catch (\Exception $exception) {
            $this->addError('Cannot save optimized XML file content');
        }
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getQp()
    {
        return $this->document;
    }

    /**
     * @param mixed $qp
     */
    public function setQp($source)
    {
        $qp = qp($source, null, $this->getOptions());
        $this->document = $qp;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return (array)$this->errors;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Add error.
     */
    public function addError($error)
    {
        array_push($this->errors, $error);
    }
}
