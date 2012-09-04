<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
interface NameCreator
{
	/**
	 * Detects what the title is based on a given text.
	 * 
	 * @param string $text
	 * @param string $file
	 */
	public function getTitle($text, $file);
}