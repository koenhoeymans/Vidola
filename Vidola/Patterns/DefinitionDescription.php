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
				return "$match[1]<dd>$match[2]</dd>";
			},
			$text
		);
	}
}