<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Blockquote implements Pattern
{
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

			(?<quote>
				(?<indentation>[ \t]*)		# indentation
				>\ .+						# followed by space and the quoted text
				(\n\g{indentation}.+)*		# following text on following line, < not
											# required anymore
			)
			(?=\n\n|$)
			@x',
			function ($match)
			{
				$text = preg_replace("#(^|\n)> ?#", "\${1}", $match['quote']);
				$start = ($match['start'] === '') ? '' : "\n\n";
				return $start . "{{blockquote}}" . $text . "{{/blockquote}}";
			},
			$text
		);
	}
}