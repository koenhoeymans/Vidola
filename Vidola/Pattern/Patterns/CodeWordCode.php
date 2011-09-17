<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class CodeWordCode implements Pattern
{
	public function replace($text)
	{
		return preg_replace_callback(
			"#(?<=\n\n)(\s+)CODE:\n+(\\1\s+)(.+(\n*\\1\s+.+)*)(?=\n\n|$)#i",
			function ($match)
			{
				$code = preg_replace("#\n$match[2](\s*.+)#", "\n\${1}", $match[3]);
				$code = htmlspecialchars($code, ENT_NOQUOTES, 'UTF-8');
				return '{{pre}}{{code}}' . $code . '{{/code}}{{/pre}}';
			},
			$text
		);
	}
}