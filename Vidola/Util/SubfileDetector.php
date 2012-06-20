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
	 * Return the immediate subfiles found in a text string.
	 * 
	 * @param string $text
	 * @return array
	 */
	public function getSubfiles($text);
}