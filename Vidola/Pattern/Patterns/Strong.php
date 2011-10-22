<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Strong implements Pattern
{
	public function replace($text)
	{
		return preg_replace(
			"@
				(?<=\s|^)
			(?<marker>\*\*|__)
				(?=\S)
				(?!\g{marker}+\B)
			(\S+|.+?(\*|_)?(\g{marker})*)
				(?<!\s)
			\g{marker}
				(?!\w)
			@x",
			"{{strong}}\${2}{{/strong}}",
			$text
		);
	}
}