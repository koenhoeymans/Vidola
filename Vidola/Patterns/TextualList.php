<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class TextualList implements Pattern
{
	public function replace($text)
	{
		return preg_replace_callback(
			'@
			(?<start>
			^								# start of text
			|
			\n*\n\n(?=([ ]{1,3})?[^\s])		# indented with max 3 spaces
			|
			\n+\n\n(?=[ \t]+)				# indented more if at least 2 blank lines 
			)

			(?<list>
			(?<indentation>[ \t]*)			# indentation
			([*+#-]|[0-9]+\.)				# list markers
			\ .+							# space and text
			(\n(\n\g{indentation})?.+)*		# continuation of list
			)

			(?<end>\n\n|$)
			@x',
			function($match)
			{
				$list = (preg_match("/([0-9]+\.|#)/", $match[4]) === 1) ? 'ol' : 'ul';
				$start = ($match['start'] !== '') ? "\n\n": '';
				$items = preg_replace(
					"#(\n|^)" . $match['indentation'] . "#", "\${1}", $match['list']
				);

				return "$start<$list>\n" . $items . "\n</$list>" . $match['end'];
			},
			$text
		);
	}
}