<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class SpecialCharacter implements Pattern
{
	public function replace($text)
	{
		return preg_replace_callback(
			"#(.*)(</?([a-z0-9]+)( [^\s]+)*>|\n|$)#iU",
			function ($match)
			{
				return htmlspecialchars($match[1], ENT_NOQUOTES, 'UTF-8', false)
					. $match[2];
			},
			$text
		);
	}
}