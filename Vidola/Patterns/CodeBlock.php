<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class CodeBlock implements Pattern
{
	/**
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		return preg_replace_callback(
			"#(?<=\n\n)(\s+)CODE:\n(\n*(\\1\s+).+(\n*\\1\s+.+)*)(?=\n\n|$)#i",
			function ($match) 
			{
				$code = preg_replace("#$match[3]#", "", $match[2]);

				return
					'<pre><code>' .
					htmlentities($code) .
					'</code></pre>';
			},
			$text
		);
	}
}