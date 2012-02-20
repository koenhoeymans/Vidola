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
	public function getRegex()
	{
		return "#(?<=\n\n)(\s+)CODE:\n+(\\1\s+)(.+(\n*\\1\s+.+)*)(?=\n\n|$)#i";
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$code = preg_replace("#\n$match[2](\s*.+)#", "\n\${1}", $match[3]);
		return $this->createCodeReplacement($code, true, $parentNode);
	}
}