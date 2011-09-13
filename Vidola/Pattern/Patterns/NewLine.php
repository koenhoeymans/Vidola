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
		$hardRet = preg_replace(
			"#(.+)(?<!  )[\r|\n](.+)#U",
			"\${1}{{br /}}\n\${2}",
			$text
		);

		$doubleSpace = preg_replace(
			"#[ ]{2,}\n#",
			"{{br /}}\n",
			$hardRet
		);

		return $doubleSpace;
	}
}