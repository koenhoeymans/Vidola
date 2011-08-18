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
		return preg_replace_callback(
			'@
				(?<=\n\n|^\n|^)				# preceded by blank line or start
				(?P<indentation>\s*)		# indentation possible
				(?P<firstline>[^\s].*)		# text
				(?P<nextlines>(\n\\1(.+))*)	# can continue on next line
				(?=\n\n|\n$)				# followed by blank line
			|								# cannot both be start and end of string
				(?<=\n\n|^\n)				# preceded by blank line
				(?P<indentation_2>\s*)
				(?P<firstline_2>[^\s].*)
				(?P<nextlines_2>(\n\\1(.+))*)
				(?=\n\n|\n$|$)				# followed by blank line or end
			@x',
			function ($match)
			{
				if ($match['firstline'] !== '')
				{
					return $match['indentation']
						. '<p>'
						. $match['firstline']
						. $match['nextlines']
						. '</p>';
				}
				else
				{
					return $match['indentation_2']
						. '<p>'
						. $match['firstline_2']
						. $match['nextlines_2']
						. '</p>';
				}
			},
			$text
		);
	}
}