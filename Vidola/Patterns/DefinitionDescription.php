<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package
 */
class DefinitionDescription implements Pattern
{
	public function replace($text)
	{
		return preg_replace_callback(
			"#(?<=\n)([\ \t]+)~?(.+(\n\n?\\1[\ \t]*[^~].+)*)#",
			function ($match)
			{
				$contents = preg_replace("$\n$match[1]$", "\n", $match[2]);
				return
					"<dd>\n"
					. $contents
					. "\n</dd>";
			},
			$text
		);
	}
}