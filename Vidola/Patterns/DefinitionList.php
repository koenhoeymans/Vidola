<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class DefinitionList implements Pattern
{
	/**
	 * term a:
	 * term b:
	 * 		-explanation
	 * 
	 * 		may contain multiple paragraphs
	 * 
	 * 		~may contain multiple descriptions (defintion description knows about tilde)
	 * 
	 * other:
	 * 		~definition
	 * 
	 * OR
	 * 
	 * term a
	 * term b
	 * 		description
	 * 
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{
		return preg_replace_callback(
			'@
			(?<start>
			^								# start of text
			|
			\n*\n\n(?=([ ]{1,3})?[^\s])		# max 3 spaces
			| 
			\n+\n\n(?=[ \t]+)				# more indentation if at least 2 blank lines 
			)

			((
			(?<t_indent>[ \t]*)				# indentation
			.+:								# term
			(\n\g{t_indent}.+:)*			# other terms with same description
			\n								# description on new line
			(?<d_indent>\g{t_indent}[ \t]+).+		# indented, text
			(\n(\n\g{d_indent})?.+)*		# following lines: text on next line or
											# blank line and text indented
			(?<end>\n\n|$))+)
			@x',
			function ($match)
			{
				# unindent
				$contents = preg_replace("#(\n|^)" . $match['t_indent'] . "#", "\${1}", "$match[3]");
				# trim last newlines so </dl> is placed right behind matching text
				$contents = rtrim($contents);
				# only one blank line even when there are more
				$start = ($match['start'] === '') ? '' : "\n\n";

				return $start . "<dl>\n$contents\n</dl>" . $match['end'];
			},
			$text
		);
	}
}