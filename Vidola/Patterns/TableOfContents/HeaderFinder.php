<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns\TableOfContents;

/**
 * @package Vidola
 */
class HeaderFinder
{
	private $header;

	public function __construct(\Vidola\Patterns\Header $header)
	{
		$this->header = $header;
	}

	public function getHeadersSequentially($text)
	{
		$headers = array();

		preg_match_all(
			"#(<h[123456]( .+?)?>.+?</h[123456]>)#",
			$this->header->replace($text),
			$taggedHeaders
		);

		foreach ($taggedHeaders[0] as $header)
		{
			$headers[] = array(
				'title' => substr($header, strpos($header, '>') + 1, -5),
				'level' => $header[2]
			);
		}

		return $headers;
	}
}