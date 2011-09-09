<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

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
					"{{dd}}"
					. $contents
					. "{{/dd}}";
			},
			$text
		);
	}
}