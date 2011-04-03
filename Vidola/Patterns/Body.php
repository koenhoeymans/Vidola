<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Body implements Pattern
{
	/**
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		if ($text === '') { return ''; }

		return "\n<body>" . $text . "\n\n</body>";
	}
}