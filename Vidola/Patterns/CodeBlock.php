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
		$codeWithCodeWord = preg_replace_callback(
			"#(?<=\n\n)(\s+)CODE:\n+(\\1\s+)(.+(\n*\\1\s+.+)*)(?=\n\n|$)#i",
			function ($match)
			{
				$code = preg_replace("#\n$match[2](\s*.+)#", "\n\${1}", $match[3]);

				return
					'<pre><code>' .
					htmlentities($code) .
					'</code></pre>';
			},
			$text
		);

		$codeWithTildes = preg_replace_callback(
			"#(?<=\n\n)(\s*)~~~.*\n+(\\1\s*)((\n|.)+?)\n+\\1~~~.*(?=\n\n)#",
			function ($match)
			{
				$code = preg_replace("#\n$match[2](\s*.+)#", "\n\${1}", $match[3]);

				return
					'<pre><code>' .
					htmlentities($code) .
					'</code></pre>';
			},
			$codeWithCodeWord
		);

		return $codeWithTildes;
	}
}