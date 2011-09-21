<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class CodeInline extends Code
{
	public function replace($text)
	{
		return preg_replace_callback(
			'@
			(?<=^|\ )
			[`](?<extra_bt>([`])*)
			(?<code>.+?)
			\g{extra_bt}[`]
			(?=\ |$)
			@x',
			array($this, 'replaceCode'),
			$text
		);
	}

	protected function replaceCode($match)
	{
		return $this->createCodeInHtml($match['code'], false);
	}
}