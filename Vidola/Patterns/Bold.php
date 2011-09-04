<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Bold implements Pattern
{
	public function replace($text)
	{
		return preg_replace(
			"#(?<=\s)\*(?![0-9]| )(.+)(?<! )\*(?!\w)#U",
			"{{b}}\${1}{{/b}}",
			$text
		);
	}
}