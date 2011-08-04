<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class DefinitionList implements Pattern
{
	public function replace($text)
	{
		return preg_replace_callback(
			'#
				(?<=^|\n\n)
				(.+)(\n.+)*
				(\n\n?[\ \t]+.+)+
				(\n\n(.+)(\n.+)*(\n\n?[\ \t]+.+)+)?
			#x',
			function ($match)
			{
				return "<dl>\n$match[0]\n</dl>";
			},
			$text
		);
	}
}