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
	public function replace($text)
	{
		return preg_replace_callback(
			'@
			(?<=^|\n\n)
			(?<code>
			(\t|[ ]{4}).*
			(\n+(\t|[ ]{4}).*)*
			)
			(?=\n\n|$)
			@x',
			array($this, 'replacecode'),
			$text
		);
	}

	protected function replaceCode($match)
	{
		$code = preg_replace("#(\n|^)(\t|    )#", "\${1}", $match['code']);
		return $this->createCodeInHtml($code);
	}
}