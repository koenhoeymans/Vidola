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
	 * Return the subfiles, as specified in the toc, in a text string.
	 * 
	 * @param string $text
	 * @return array
	 */
	public function getSubfiles($text);
}