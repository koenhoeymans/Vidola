<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\TextProcessor;

/**
 * @package Vidola
 */
class SpecialCharacterPostTextHandler implements TextProcessor
{
	public function process($text)
	{
		# $dom->saveXML (see recursive replacer)
		# translated &, > and < everywhere to &amp; etc
		$text = $this->unneutralizeNeutralizedByDom($text);

		return $text;
	}

	private function unneutralizeNeutralizedByDom($text)
	{
		return html_entity_decode($text);
	}
}