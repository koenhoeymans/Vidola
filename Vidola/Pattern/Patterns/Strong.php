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
			"#(?<=\s)\*\*(?![0-9]| )(.+)(?<! )\*\*(?!\w)#U",
			"{{strong}}\${1}{{/strong}}",
			$text
		);
	}
}