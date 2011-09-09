<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns\TableOfContents;

/**
 * @package Vidola
 */
class HeaderFinder
{
	private $header;

	public function __construct(\Vidola\Pattern\Patterns\Header $header)
	{
		$this->header = $header;
	}

	public function getHeadersSequentially($text)
	{
		$headers = array();

		preg_match_all(
			"#({{h[123456]( .+?)?}}.+?{{/h[123456]}})#",
			$this->header->replace($text),
			$taggedHeaders
		);

		foreach ($taggedHeaders[0] as $header)
		{
			$headers[] = array(
				'title' => substr($header, strpos($header, '}') + 2, -7),
				'level' => $header[3]
			);
		}

		return $headers;
	}
}