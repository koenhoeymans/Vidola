<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Bold implements Pattern
{
	public function replace($text)
	{
		return preg_replace(
			"#(?<![0-9])\*(?![0-9]| )(.+)(?<! )\*#U",
			"<b>\${1}</b>",
			$text
		);
	}
}