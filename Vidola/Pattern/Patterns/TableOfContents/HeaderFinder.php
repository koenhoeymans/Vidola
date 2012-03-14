<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns\TableOfContents;

use \Vidola\Pattern\Patterns\Header;

/**
 * @package Vidola
 */
class HeaderFinder
{
	private $header;

	public function __construct(Header $header)
	{
		$this->header = $header;
	}

	public function getHeadersSequentially($text)
	{
		$headers = array();

		preg_match_all(
			$this->header->getRegex(),
			$text,
			$headerMatches,
			PREG_SET_ORDER
		);

		foreach ($headerMatches as $headerMatch)
		{
			$headerNode = $this->header->handleMatch($headerMatch, new \DOMDocument());
			$headers[] = array(
				'title' => $headerNode->nodeValue,
				'level' => substr($headerNode->nodeName, 1),
				'id'	=> $headerNode->getAttribute('id')
			);
		}

		return $headers;
	}
}