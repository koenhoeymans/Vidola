<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Paragraph implements Pattern
{
	/**
	 * Empty line, text, empty line.
	 */
	public function replace($text)
	{
		# Cannot both be start and end of text.
		# This is to avoid that eg simple list items
		# are regarded as parameters.
		if (!preg_match("#^\n|\n\n#",$text))
		{
			return $text;
		}

		return preg_replace_callback(
			'@
			(										# before
			^\n?(?=[ ]{0,3}[^\s])
			|
			\n\n\n(?=[ \t]*[^\s])
			|
			\n\n(?=[ ]{0,3}[^\s])
			)
			(?<indentation>[ \t]*)					# indentation
			(?<contents>
				(?(?=<)								# first line
				<									# trying to avoid tags
				(?!\S[a-z0-9]*[a-z0-9 ]*>\n
				|
				p>
				|
				!--[.\n]*?-->
				).*
				|
				[^\s].*)

				(\n\g{indentation}?					# next lines
				(?(?=<)
				<(?!\S[a-z0-9]*[a-z0-9 ]*>\n).*
				|
				[^\s].*)
				)*
			)
			(?=\n\n|\n$|$)							# after
			@x',
			function ($match)
			{
				# unindent
				$paragraph = preg_replace("#(^|\n)[ \t]+#", "\${1}", $match['contents']);
				$before = preg_replace("#\n\n\n*#", "\n\n", $match[1]);

				return $before . "{{p}}" . $paragraph . "{{/p}}";
			},
			$text
		);
	}
}