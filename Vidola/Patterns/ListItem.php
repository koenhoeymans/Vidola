<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class ListItem implements Pattern
{
	public function replace($text)
	{
		return preg_replace_callback(
			"/(((\t| )+)([*+#-]|[0-9]+\.) )(.+(\n[^\s\n].+|\n\n?\\2(?![*+#-]|[0-9]+\.).+)*)/",
			function($match)
			{
				return "<li>$match[5]</li>";
			},
			$text
		);
	}
}