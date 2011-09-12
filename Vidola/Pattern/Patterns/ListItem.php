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
	private $markers = "[*+-]|[0-9]+\.";

	public function replace($text)
	{
		return preg_replace_callback(
			'@
			(
			((\t|\ )*)					# can be indented
			([*+#-]|[0-9]+\.)			# markers
			\ 							# a space
			)
			(?<content>
			.+							# text of first line
			(							# optionally
			\n(?!\\2([*+#-]|[0-9]+\.)).+	# continues on next line unindented
			|
			\n\n?\\2(?![*+#-]|[0-9]+\.).+	# or indented
			)*
			)
			@x',
			function($match)
			{
				return '{{li}}' . $match['content'] . '{{/li}}';
			},
			$text
		);
	}
}