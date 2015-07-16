<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class DocFileRetriever implements ContentRetriever
{
    private $extensions = array('txt', 'text', 'md');

    private $sourceDir = '';

    public function setSourceDir($dir)
    {
        $this->sourceDir = realpath($dir);
    }

    /**
     * Retrieves the file relative to the current directory and
     * the source directory. It searches for `.txt` and `.text`
     * files.
     *
     * @see Vidola\Util.ContentRetriever::retrieve()
     */
    public function retrieve($file)
    {
        if (file_exists($file)) {
            return file_get_contents($file);
        }

        foreach ($this->extensions as $extension) {
            if (file_exists($file . '.' . $extension)) {
                return file_get_contents($file . '.' . $extension);
            }

            if (file_exists($this->sourceDir . DIRECTORY_SEPARATOR.$file . '.' . $extension)) {
                return file_get_contents(
                    $this->sourceDir . DIRECTORY_SEPARATOR . $file . '.' . $extension
                );
            }
        }

        $ucFile = ucfirst($file);
        if ($ucFile !== $file) {
            return $this->retrieve($ucFile);
        }

        throw new \Exception(
            'DocFileRetriever::retrieveContent() couldn\'t find "' . $file . '"'
        );
    }
}
