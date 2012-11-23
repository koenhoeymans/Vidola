<?php

/**
 * @package
 */
namespace Vidola\Util;

/**
 * @package
 */
use Vidola\Pattern\Patterns\TableOfContents;

class HtmlHeaderBasedTocGenerator implements TocGenerator
{
	public function __construct(TableOfContents $toc)
	{
		$this->toc = $toc;
	}

	public function createTocNode(\DomDocument $domDoc)
	{
		return $this->toc->createTocNode($domDoc);
	}
}