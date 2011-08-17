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
			(?<=^|\n\n)
			.+:					# term
			(\n.+:)*			# other terms
			\n[\ \t]+.+			# explanation
			(\n\n[\ \t]+.+)*	# explanation can contain paragraphs
			(\n\n(.+):(\n.+)*(\n\n?[\ \t]+.+)+)?	# other definitions
			@x',
			function ($match)
			{
				return "<dl>\n$match[0]\n</dl>";
			},
			$text
		);
	}
}