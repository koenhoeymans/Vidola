<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Head implements Pattern
{
	/**
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		if ($text === '') { return ''; }

		return "\n<head><title>title</title></head>" . $text;
	}
}