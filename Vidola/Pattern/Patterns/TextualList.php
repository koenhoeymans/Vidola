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
			\n+\n(?=([ ]{0,3})\S)			# indented with max 3 spaces
			|
			\n(?=[ ]{1,3}\S)				# new line and indented 1-3 spaces
			|
			(^|\n)\S.*\n(?=\t\S)			# tabbed, after unindented non-blank line 
			)

			(?<list>
			(?<indentation>[ \t]*)			# indentation
			(
				(?<ul>[*+-])				# unordered list markers
				(?<a>[ \t]+)\S.*						# space and text
				(\n								# continuation of list: newline
				(\n\g{indentation}[*+-]?\g{a})?				# or two lines for paragraph
 
				.+)*
			|
				(?<ol>([0-9]+|\#)\.)		# ordered list markers
				(?<b>[ \t]+)\S.*					# space and text
				(\n								# continuation of list: newline
				(\n\g{indentation}(([0-9]+|\#)\.)?\g{b})?				# or two lines for paragraph
 
				.+)*
			)
			)
			(?=(?<blank_line_after>\n\n)|$)
			@x',
			function($match)
			{
				$list = (isset($match['ol']) && ($match['ol'] !== '')) ? 'ol' : 'ul';

				# multiple blank lines before the list are reduced to one blank line
				$start = preg_replace("#^(\n\n?)\n*#", "\${1}", $match['start']);

				# list is unindented
				$items = preg_replace(
					"#(\n|^)" . $match['indentation'] . "#", "\${1}", $match['list']
				);

				$list = $start
					. "{{" . $list . "}}\n" . $items . "\n{{/" . $list . "}}";

				# creates extra line between list and preceding non-empty line
				if(isset($match['blank_line_after']))
				{
					$list = preg_replace(
						"@([^\n]+)(\n{{(ol|ul)}}.+)$@s", "\${1}\n\${2}", $list
					);
				}
				
				return $list;
			},
			$text
		);
	}
}