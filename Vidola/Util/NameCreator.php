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
	 * Detects what the name is based on a given text.
	 * 
	 * @param string $text
	 */
	public function getName($text);
}