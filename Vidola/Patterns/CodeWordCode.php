<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class CodeWordCode implements Pattern
{
	/**
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		return preg_replace_callback(
			"#(?<=\n\n)(\s+)CODE:\n+(\\1\s+)(.+(\n*\\1\s+.+)*)(?=\n\n|$)#i",
			function ($match)
			{
				$code = preg_replace("#\n$match[2](\s*.+)#", "\n\${1}", $match[3]);

				return
					'{{code}}' .
					htmlentities($code) .
					'{{/code}}';
			},
			$text
		);
	}
}