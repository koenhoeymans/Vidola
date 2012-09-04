<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use Vidola\Pattern\Patterns\TableOfContents;
use Vidola\Pattern\Patterns\TableOfContents\HeaderFinder;

/**
 * @package Vidola
 */
class HeaderBasedNameCreator implements NameCreator
{
	private $headerFinder;

	private $toc;

	public function __construct(HeaderFinder $headerFinder, TableOfContents $toc)
	{
		$this->headerFinder = $headerFinder;
		$this->toc = $toc;
	}

	public function getTitle($text, $file)
	{
		$specifiedTitle = $this->toc->getSpecifiedTitleForPage($file);

		if ($specifiedTitle)
		{
			return $specifiedTitle;
		}

		$headers = $this->headerFinder->getHeadersSequentially($text);

		if (empty($headers))
		{
			return null;
		}

		$firstHeader = array_shift($headers);

		return $firstHeader['title'];
	}
}