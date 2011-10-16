<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\Processor;

/**
 * @package Vidola
 */
class HtmlPrettifier implements Processor
{
	public function process($text)
	{
		$text = preg_replace("@</p>\n\n</li>@", "</p></li>", $text);
		$text = preg_replace("@</ul>\n\n</li>@", "</ul></li>", $text);
		$text = preg_replace("@</li>\n\n<li>@", "</li>\n<li>", $text);

		return $text;
	}
}