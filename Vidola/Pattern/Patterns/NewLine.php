<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class NewLine implements Pattern
{
	public function replace($text)
	{
		$doubleSpace = preg_replace(
			"#[ ]{2,}\n#",
			"{{br /}}\n",
			$text
		);

		return $doubleSpace;
	}
}