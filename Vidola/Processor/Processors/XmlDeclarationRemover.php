<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\TextProcessor;

/**
 * @package Vidola
 */
class XmlDeclarationRemover implements TextProcessor
{
	public function process($text)
	{
		return preg_replace("@^\<\?xml version.+\n@", "", $text);
	}
}