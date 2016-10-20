<?php

/**
 * @file File.php
 */

namespace WordPress2Drupal\Document;

/**
 * Class File
 * @package WordPress2Drupal\Document
 */
class File
{
    protected $filepath;
    protected $filename;
    protected $filemime;
    protected $size;

    /**
     * File constructor.
     * @param null $filepath
     */
    public function __construct($filepath = null)
    {
        $this->setFilepath($filepath);
    }

    /**
     * @return mixed
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * @param mixed $filepath
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
        $this->setFilename(basename($filepath));
        $this->setFilemime(mime_content_type($filepath));
        $this->setSize(@filesize($filepath));
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getFilemime()
    {
        return $this->filemime;
    }

    /**
     * @param mixed $filemime
     */
    public function setFilemime($filemime)
    {
        $this->filemime = $filemime;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }
}