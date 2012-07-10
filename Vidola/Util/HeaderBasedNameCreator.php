<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use Vidola\Pattern\Patterns\TableOfContents\HeaderFinder;

/**
 * @package Vidola
 */
class HeaderBasedNameCreator implements NameCreator
{
	private $headerFinder;

	public function __construct(HeaderFinder $headerFinder)
	{
		$this->headerFinder = $headerFinder;
	}

	public function getTitle($text)
	{
		$headers = $this->headerFinder->getHeadersSequentially($text);

		if (empty($headers))
		{
			return null;
		}

		$firstHeader = array_shift($headers);

		return $firstHeader['title'];
	}
}