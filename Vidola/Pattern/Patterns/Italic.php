<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Italic implements Pattern
{
	public function replace($text)
	{
		return preg_replace(
			"@
				(?<=\s|^)
			_
				(?=\S)
				(?!_+\B)
			(.+)
				(?<!\s)
			_
				(?!\w)
			@xU",
			"{{i}}\${1}{{/i}}",
			$text
		);
	}
}