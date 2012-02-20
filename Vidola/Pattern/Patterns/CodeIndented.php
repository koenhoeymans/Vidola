<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class CodeIndented extends Code
{
	public function getRegex()
	{
		return
			'@
			(?<=^|\n\n|(?<newline>^\n))
			(?<code>
			(\t|[ ]{4}).*
			(\n+(\t|[ ]{4}).*)*
			)
			(?=\n\n|\n$|$)
			@x';
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		if ($parentPattern && $match['newline'] === "\n")
		{
			if ($parentPattern instanceof \Vidola\Pattern\Patterns\ManualHtml)
			{
				return false;
			}
		}

		$code = preg_replace("#(\n|^)(\t|[ ]{4})#", "\${1}", $match['code']);
		return $this->createCodeReplacement($code, true, $parentNode);
	}
}