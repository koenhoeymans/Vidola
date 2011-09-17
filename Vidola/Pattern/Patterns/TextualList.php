<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

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
			^\n(?=([ ]{0,3})[^\s])
			|
			\n+\n(?=([ ]{0,3})[^\s])		# indented with max 3 spaces
			|
			\n+\n\n(?=[ \t]+)				# indented more if at least 2 blank lines 
			)

			(?<list>
			(?<indentation>[ \t]*)			# indentation
			(
				(?<ul>[*+-])				# unordered list markers
				\ [^\s].*						# space and text
				(\n								# continuation of list: newline
				(\n\g{indentation}				# or two lines for paragraph
				(\ |[*+-])\ )?					# or new item 
				.+)*
			|
				(?<ol>([0-9]+|\#)\.)		# ordered list markers
				\ [^\s].*						# space and text
				(\n								# continuation of list: newline
				(\n\g{indentation}				# or two lines for paragraph
				(\ |[0-9]+|\#)\.)?				# or new item 
				.+)*
			)
			)
			(?=\n\n|$)
			@x',
			function($match)
			{
				$list = isset($match['ol']) ? 'ol' : 'ul';
				$start = preg_replace("#^(\n\n?)\n*#", "\${1}", $match['start']);
				$items = preg_replace(
					"#(\n|^)" . $match['indentation'] . "#", "\${1}", $match['list']
				);

				return $start . "{{" . $list . "}}\n" . $items . "\n{{/" . $list . "}}";
			},
			$text
		);
	}
}