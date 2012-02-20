<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\TextProcessor;

/**
 * @package Vidola
 */
class SpecialCharacterPostHandler implements TextProcessor
{
	public function process($text)
	{
		$text = $this->removeXmlDeclaration($text);
		# $dom->saveXML (see recursive replacer)
		# translated &, > and < everywhere to &amp; etc
		$text = $this->unneutralizeNeutralizedByDom($text);

		return $text;
	}

	private function removeXmlDeclaration($text)
	{
		return preg_replace("@\<\?xml version.+\n@", "", $text);
	}

	private function unneutralizeNeutralizedByDom($text)
	{
		return html_entity_decode($text);
	}
}