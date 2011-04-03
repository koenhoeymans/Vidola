<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Document implements Pattern
{
	public function replace($text)
	{
		if ($text === '') { return ''; }

		return
			"<html>\n" . $text . "</html>";
	}
}