<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
interface SubfileDetector
{
	/**
	 * Find the subfiles given in a text string through the toc.
	 * 
	 * @param string $text
	 * @return array
	 */
	public function detectSubfiles($text);
}