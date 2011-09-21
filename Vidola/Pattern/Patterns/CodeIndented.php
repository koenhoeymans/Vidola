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
			(?<pre_code>
			(?<indentation>[ \t]*)
			.*\n\n
			)
			(?<code>
			\g{indentation}(\t|[ ]{4}).*
			(\n+\g{indentation}(\t|[ ]{4}).*)*
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
		return $match['pre_code'] . $this->createCodeInHtml($code);
	}
}