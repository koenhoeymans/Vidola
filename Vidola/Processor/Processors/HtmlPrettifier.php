<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\TextProcessor;

/**
 * @package Vidola
 */
class HtmlPrettifier implements TextProcessor
{
	public function process($text)
	{
		$text = preg_replace("@</p>\n\n</li>@", "</p></li>", $text);
		$text = preg_replace("@</ul>\n\n</li>@", "</ul></li>", $text);
		$text = preg_replace("@</li>\n\n<li>@", "</li>\n<li>", $text);

		return $text;
	}
}