<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
interface TitleCreator
{
    /**
     * Detects what the title is based on a given text.
     *
     * @param string $text
     * @param string $file
     */
    public function createPageTitle($text, $file);
}
