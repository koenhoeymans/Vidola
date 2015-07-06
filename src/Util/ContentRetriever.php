<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
interface ContentRetriever
{
    /**
     * Retrieve the contents of a file by the projects internal name.
     *
     * @param string $file
     */
    public function retrieve($file);
}
