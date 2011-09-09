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
		return preg_replace(
			"#(.+)[\r|\n](.+)#U",
			"\${1}{{br /}}\n\${2}",
			$text
		);
	}
}