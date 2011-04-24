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
		return preg_replace_callback(
			"#(\[(.+?)\])?( )?\[([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})\]#i",
			function ($match)
			{
				$address = $match[4];
				if ($match[2] === '')
				{
					$anchorText = $match[4];
					$before = $match[3];
				}
				else
				{
					$anchorText = $match[2];
					$before = '';
				}

				return "$before<a href=\"mailto:$address\">$anchorText</a>";
			},
			$text
		);
		$anchorText = $match[1];
		return "<a href=\"mailto:$match[4]\">$match[2]</a>";
		return preg_replace(
			"#\[([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})\]#i",
			"<a href=\"mailto:\${1}\">\${1}</a>",
			$text
		);
	}
}