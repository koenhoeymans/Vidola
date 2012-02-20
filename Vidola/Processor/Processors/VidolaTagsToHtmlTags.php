<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\TextProcessor;

/**
 * @package Vidola
 */
class VidolaTagsToHtmlTags implements TextProcessor
{
	public function process($text)
	{
		return preg_replace("#{{(/?.+?)}}#", "<\${1}>", $text);
	}
}