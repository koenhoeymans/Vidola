<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class HorizontalRule implements Pattern
{
	public function replace($text)
	{
		return preg_replace_callback(
			'@
			\n
			([ ]{0,3}(?<marker>-|\*|_))
			([ ]{0,3}\g{marker}){2,}
			\n
			@x',
			function ($match)
			{
				return "\n{{hr /}}\n";
			},
			$text
		);
	}
}