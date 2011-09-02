<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class CodeIndented implements Pattern
{
	/**
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		return preg_replace_callback(
			'@
			(
			(?<indentation>[ \t]*)
			.*\n\n
			)
			(?<code>
			\g{indentation}(\t|[ ]{4}).*
			(\n+\g{indentation}(\t|[ ]{4}).*)*
			)
			(?=\n\n|$)
			@x',
			function ($match)
			{
				$code = preg_replace("#(\n|^)(\t|    )#", "\${1}", $match['code']);
				return $match[1] . "<code>" . $code . "</code>";
			},
			$text
		);
	}
}