<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\Processor;

/**
 * @package Vidola
 */
class VidolaTagsToHtmlTags implements Processor
{
	public function process($text)
	{
		return preg_replace("#{{(/?.+?)}}#", "<\${1}>", $text);
	}
}