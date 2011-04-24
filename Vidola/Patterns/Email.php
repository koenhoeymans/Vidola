<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Email implements Pattern
{
	/**
	 * http://www.regular-expressions.info/email.html
	 * 
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		return preg_replace(
			"#\[([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})\]#i",
			"<a href=\"mailto:\${1}\">\${1}</a>",
			$text
		);
	}
}