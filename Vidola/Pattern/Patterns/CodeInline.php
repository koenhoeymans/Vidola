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
		return preg_replace_callback(
			'@
			(?<number_bt>[`]+)
			(.+?)
			\g{number_bt}
			@x',
			function ($match)
			{
				$code = $match[2];
				$code = htmlspecialchars($code, ENT_NOQUOTES, 'UTF-8');
				return '{{code}}' . $code . '{{/code}}';
			},
			$text
		);
	}
}