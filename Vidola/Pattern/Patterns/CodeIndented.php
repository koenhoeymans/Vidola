<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class CodeIndented implements Pattern
{
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
				$code = htmlspecialchars($code, ENT_NOQUOTES, 'UTF-8');
				return $match[1] . "{{pre}}{{code}}" . $code . "{{/code}}{{/pre}}";
			},
			$text
		);
	}
}