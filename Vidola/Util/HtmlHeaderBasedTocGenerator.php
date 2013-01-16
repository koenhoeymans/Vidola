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

	/**
	 * @see Vidola\Util.TocGenerator::createTocNode()
	 */
	public function createTocNode(\DomDocument $domDoc, $maxDepth = null)
	{
		return $this->toc->createTocNode($domDoc, $maxDepth);
	}
}