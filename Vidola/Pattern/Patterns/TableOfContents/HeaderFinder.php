<?php

/**
 * @package AnyMark
 */
namespace Vidola\Pattern\Patterns\TableOfContents;

use \AnyMark\Pattern\Patterns\Header;

/**
 * @package AnyMark
 */
class HeaderFinder
{
	private $header;

	public function __construct(Header $header)
	{
		$this->header = $header;
	}

	/**
	 * Creates array with arrays of headers in the order found in the text.
	 * 
	 * array(
	 * 	array('first title' => 'foo', 'level' => 1, 'id' => 'bar')
	 * );
	 * 
	 * @param string $text
	 * @return array An array with headers, keys are 'title', 'level' and 'id'.
	 */
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
			$header = $this->header->handleMatch($headerMatch, new \ElementTree\ElementTree());
			$headers[] = array(
				'title' => $header->getChildren()[0]->getValue(),
				'level' => substr($header->getName(), 1),
				'id'	=> $header->getAttributeValue('id')
			);
		}

		return $headers;
	}
}