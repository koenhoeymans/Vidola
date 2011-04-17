<?php

/**
 * @package Vidola
 */
namespace Vidola\TextReplacer;

/**
 * @package vidola
 * 
 * Replaces the raw text with a certain format.
 */
interface TextReplacer
{
	/**
	 * The extension that belongs with the type of text.
	 * 
	 * @return string
	 */
	public function getExtension();

	/**
	 * @param string $text
	 * @return string The replaced text.
	 */
	public function replace($text);
}