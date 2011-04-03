<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class TextualList implements Pattern
{
	public function replace($text)
	{
		return preg_replace_callback(
			"/(?<=\n\n)(((\s+)([*+#-]|[0-9]+\.) ).+(\n[^\s\n].+|\n\n?\\3.+)*)(?=\n\n)/",
			function($match)
			{
				$list = (preg_match("/([0-9]+\.|#)/", $match[4]) === 1) ? 'ol' : 'ul';

				return "<$list>\n" . $match[1] . "\n</$list>";
			},
			$text
		);
	}
}