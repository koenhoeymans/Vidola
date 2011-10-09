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
			[`](?<extra_backticks>([`])*)
			(?<code>.+?)
			\g{extra_backticks}[`]
			@x',
			array($this, 'replaceCode'),
			$text
		);
	}

	protected function replaceCode($match)
	{
		# if code between backticks starts or ends with code between
		# backticks: remove the spacing
		$code = preg_replace("#^\s*(.+?)\s*$#", "\${1}", $match['code']);

		return $this->createCodeInHtml($code, false);
	}
}