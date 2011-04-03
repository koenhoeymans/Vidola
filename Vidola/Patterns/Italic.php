<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Italic implements Pattern
{
	public function replace($text)
	{
		return preg_replace(
			"#_(?! )(.+)(?<! )_#U",
			"<i>\${1}</i>",
			$text
		);
	}
}