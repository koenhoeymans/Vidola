<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

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