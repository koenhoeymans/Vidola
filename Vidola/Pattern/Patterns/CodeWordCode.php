<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class CodeWordCode extends Code
{
	public function replace($text)
	{
		return preg_replace_callback(
			"#(?<=\n\n)(\s+)CODE:\n+(\\1\s+)(.+(\n*\\1\s+.+)*)(?=\n\n|$)#i",
			array($this, 'replaceCode'),
			$text
		);
	}

	protected function replaceCode($match)
	{
		$code = preg_replace("#\n$match[2](\s*.+)#", "\n\${1}", $match[3]);
		return $this->createCodeInHtml($code);
	}
}