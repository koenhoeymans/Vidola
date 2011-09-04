<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class CodeInline implements Pattern
{
	public function replace($text)
	{
		return preg_replace(
			'#´(.+)?´#',
			"{{code}}\${1}{{/code}}",
			$text
		);
	}
}