<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Hyperlink implements Pattern
{
	public function replace($text)
	{
		$linkInText = preg_replace_callback(
			"#\[http://(.+)( \"(.+)\")?\]#U",
			function($match)
			{
				$title = isset($match[3]) ? ' title="' . $match[3] . '"' : '';

				return
					'<a'
					. $title
					. ' href="http://' . $match[1] . '">http://' . $match[1]
					. '</a>';
			},
			$text
		);

		$linkReference = preg_replace_callback(
			"#\[(?<!http://)(.+) \"(.+)\"\]((.|\n)+)\n\[\\2\]: http://(.+)\n#U",
			function($match)
			{
				return
					'<a title="' . $match[2] . '" href="http://' . $match[5] . '">'
					. $match[1]
					. '</a>'
					. $match[3];
			},
			$linkInText
		);

		return $linkReference;
	}
}