<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Emphasis implements Pattern
{
	public function replace($text)
	{
		return preg_replace(
			"@
				(?<=\s|^)
			\*
				(?=\S)
				(?!\*+($|[ ]))
			(\S+|.+?(\*\*)?)
				(?<!\s)
			\*
				(?!\w)
			@x",
			"{{em}}\${2}{{/em}}",
			$text
		);
	}
}