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
	public function replace($text);
}