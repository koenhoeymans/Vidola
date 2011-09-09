<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

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