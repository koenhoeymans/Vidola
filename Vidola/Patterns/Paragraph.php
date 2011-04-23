<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Paragraph implements Pattern
{
	/**
	 * Empty line, text, empty line.
	 * 
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		return preg_replace(
			"#(?<=\n\n)(\s*)([^\s].*)((\n\\1(.+))*)(?=\n\n|\n$|$)#",
			"\${1}<p>\${2}\${3}</p>",
			$text
		);
	}
}