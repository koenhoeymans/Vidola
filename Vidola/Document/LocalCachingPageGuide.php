<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use AnyMark\AnyMark;
use Vidola\Util\TitleCreator;
use Vidola\Pattern\Patterns\TableOfContents;

/**
 * @package Vidola
 */
class LocalCachingPageGuide implements PageGuide
{
	private $content = array();

	private $anyMark;

	private $titleCreator;

	private $toc;

	public function __construct(
		AnyMark $anyMark, TitleCreator $titleCreator, TableOfContents $toc
	) {
		$this->anyMark = $anyMark;
		$this->titleCreator = $titleCreator;
		$this->toc = $toc;
	}

	/**
	 * @see Vidola\Document.PageGuide::getParsedContent()
	 */
	public function getParsedContent(Page $page, $dom = false)
	{
		# caching contents prevents from parsing more than once on next request
		# thus for \AnyMark\Pattern\Patterns\Header to assign another unique id
		$id = $page->getUrl();
		if (isset($this->content[$id]))
		{
			$domContent = $this->content[$id];
		}
		else
		{
			$rawContent = $page->getRawContent();
			$domContent = $this->anyMark->parse($rawContent, true);
			$this->content[$id] = $domContent;
		}

		if ($dom)
		{
			return $domContent;
		}

		return $this->anyMark->saveXml($domContent);
	}

	/**
	 * @see Vidola\Document.PageGuide::getTitle()
	 */
	public function getTitle(Page $page)
	{
		return $this->titleCreator->createPageTitle(
			$page->getRawContent(), $page->getUrl()
		);
	}

	/**
	 * @see Vidola\Document.PageGuide::getToc()
	 */
	public function getToc(Page $page, $maxDepth = null)
	{
		return $this->toc->createTocNode(
			$this->getParsedContent($page, true), $maxDepth
		);
	}
}