<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Paragraph implements Pattern
{
	/**
	 * Empty line, text, empty line.
	 * 
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		return preg_replace(
			'@
				(?<=\n\n)				# preceded by blank line
				(\s*)([^\s].*)			# text optionally indented
				((\n					# can continue on next line of indented
					(\n(?=\\1\s))?		# a blank line is possible when text is extra indented
				\\1(.+))*)				# to allow eg for a code block
				(?=\n\n(?!\\1\s)|\n$|$)	# followed by blank line or end
			@x',
			"\${1}<p>\${2}\${3}</p>",
			$text
		);
	}
}