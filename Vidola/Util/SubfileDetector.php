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
	 * Return the subfiles given in a text string in the toc.
	 * 
	 * @param string $text
	 * @return array
	 */
	public function detectSubfiles($text);
}