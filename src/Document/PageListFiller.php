<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
use Vidola\Util\ContentRetriever;
use Vidola\Pattern\Patterns\TableOfContents;

class PageListFiller
{
	private $contentRetriever;

	private $toc;

	private $pages = array();

	public function __construct(ContentRetriever $contentRetriever, TableOfContents $toc)
	{
		$this->contentRetriever = $contentRetriever;
		$this->toc = $toc;
	}

	public function fill(PageList $pageList, $startPageName)
	{
		if (in_array($startPageName, $this->pages))
		{
			return;
		}

		$text = $this->contentRetriever->retrieve($startPageName);
		$page = $this->createPage($startPageName, $text);

		$pageList->add($page);

		$this->fillRecursively($pageList, $page, $this->toc->getSubpages($text));
	}

	private function fillRecursively(PageList $pageList, Page $page, array $subpageNames)
	{
		foreach ($subpageNames as $subpageName)
		{
			$text = $this->contentRetriever->retrieve($subpageName);
			$subpage = $this->createPage($subpageName, $text);
			$pageList->add($subpage, $page);

			$this->fillRecursively($pageList, $subpage, $this->toc->getSubpages($text));
		}
	}

	private function createPage($name, $text)
	{
		return new \Vidola\Document\Page($name, $text);
	}
}