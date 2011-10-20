<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class ListItem implements Pattern
{
	protected $markers = "([*+#-]|[0-9]+\.|\#\.)";

	public function replace($text)
	{
		$markers = $this->markers;
		return preg_replace_callback(
			'@
			(?<=(?<para_before>\n\n)|^|\n)

			# setting the structure for the list item
			# ----------------------------------------
			(
			(?<marker_indent>[ \t]*)	# indentation of the list marker
			' . $this->markers . '		# markers
			(?<text_indent>[ ]{1,3}|\t|(?=\n))	# spaces/tabs
			)

			# the list item content
			# ---------------------
			(?<content>
			.*						# text of first line
				(						# optionally more lines
				\n							# continue on next line unindented
					(?!
						\g{marker_indent}
						' . $this->markers . '
					)
				.+
				|							# or indented
				\n\n?\g{marker_indent}
					(?!' . $this->markers . ')
				.+
				)*
			)

			(?=
				(?<para_after>\n\n\g{marker_indent}' . $this->markers . '.+)
				|
				\n
				|
				$
			)
			@x',
			function($match)
			{
				$paragraph = (($match['para_before'] != "") || isset($match['para_after']))
					? "\n\n" : "";
				$content = preg_replace(
					"@\n" . $match['marker_indent'] .  "[ ]?" . $match['text_indent'] . "?@",
					"\n",
					$match['content']
				);

				return
					'{{li}}'
					. $content
					. $paragraph
					. '{{/li}}';
			},
			$text
		);
	}
}