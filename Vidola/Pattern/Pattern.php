<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern;

/**
 * @package vidola
 * 
 * When a text matches its pattern it transforms it.
 */
interface Pattern
{
	public function replace($text);
}