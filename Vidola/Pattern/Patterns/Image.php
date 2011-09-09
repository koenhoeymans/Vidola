<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Image implements Pattern
{
	public function replace($text)
	{
		return $this->replaceReference($this->replaceInline($text));
	}

	private function replaceInline($text)
	{
		return preg_replace_callback(
			"#\[img: http://(.+)( ([\"\'])(.+)\\3)?]#U",
			function($match)
			{
				$alt = (isset($match[2])) ? "alt=\"$match[4]\" " : "";
				return "{{img " . $alt . "src=\"http://" . $match[1] . "\"}}";
			},
			$text
		);
	}

	/**
	 * @todo replace circular handling
	 */
	private function replaceReference($text)
	{
		$replaced = preg_replace_callback(
			"#\[img: ([\"\'])(.+)\\1\]((.|\n)*\n)\[\\2\]: http://(.+)(\n|$)#U",
			function($match)
			{
				$alt = "alt=\"$match[2]\" ";
				return "{{img " . $alt . "src=\"http://" . $match[5] . "\"}}" . $match[3];
			},
			$text
		);

		if ($replaced == $text)
		{
			return $replaced;
		}

		return $this->replaceReference($replaced);
	}
}