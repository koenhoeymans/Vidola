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
		# This is to avoid that the contents of
		# eg simple list items are regarded as
		# paragraphs.
		if (!(preg_match("#^\n#",$text) && preg_match("#\n$#",$text))
			&& !preg_match("#\n\n#",$text))
		{
			return $text;
		}

		return preg_replace_callback(
			'@
			(?<before>								# before
			^(?=[ ]{0,3}\S)
			|
			^\n(?=[ ]{0,3}\S)
			|
			\n\n(?=[ ]{0,3}\S)
			|
			\n\n\n(?=[ \t]*\S)
			)
			(?<indentation>[ \t]*)					# indentation
			(?<contents>
				(?(?=<)								# if first line starts with <
					<
					(?!
					h[1-6]
					|
					p[ ]
					|
					(/?(hr|div|pre|p|li|dl|blockquote|table)[a-z0-9 \"\'=]*/?>
					|
					!--(.|\n)*?-->)						# nor a comment
					)
					.*
				|									# line does not start with <
					.*
				)

				(\n\g{indentation}?					# next lines
					(?(?=<)
					<(?!\S[a-z0-9]*[a-z0-9 ]*>\n).*
					|
					\S.*)
				)*
			)
			(?=\n\n|\n$|$)							# after
			@xi',
			function ($match)
			{
				# unindent
				$paragraph = preg_replace("#(^|\n)[ \t]+#", "\${1}", $match['contents']);
				$before = preg_replace("#\n\n\n*#", "\n\n", $match['before']);

				return $before . "{{p}}" . $paragraph . "{{/p}}";
			},
			$text
		);
	}
}