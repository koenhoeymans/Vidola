<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use AnyMark\Util\InternalUrlBuilder;
use Vidola\Processor\TextProcessor;
use AnyMark\AnyMark;
use Vidola\Util\TitleCreator;
use Vidola\Util\TocGenerator;

/**
 * @package Vidola
 */
class MarkdownBasedDocumentation implements DocumentationApiBuilder, FilenameCreator, PageGuide, Structure
{
	private $content = array();

	private $anyMark;

	private $titleCreator;

	private $tocGenerator;

	private $internalUrlBuilder;

	/**
	 * List of Page objects.
	 *
	 * @var array
	 */
	private $pages = array();

	/**
	 * $pageUrl => ParentPage
	 *
	 * @var array
	 */
	private $parentPages = array();

	public function __construct(
		AnyMark $anyMark,
		TitleCreator $titleCreator,
		TocGenerator $tocGenerator,
		InternalUrlBuilder $internalUrlBuilder
	) {
		$this->anyMark = $anyMark;
		$this->titleCreator = $titleCreator;
		$this->tocGenerator = $tocGenerator;
		$this->internalUrlBuilder = $internalUrlBuilder;
	}

	/**
	 * @see Vidola\Document.DocumentationApiBuilder::buildApi()
	 */
	public function buildApi(Page $page)
	{
		return new \Vidola\Document\MarkdownBasedDocumentationViewApi($page, $this, $this);
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
		return $this->tocGenerator->createTocNode(
			$this->getParsedContent($page, true), $maxDepth
		);
	}

	/**
	 * @see Vidola\Document.PageList::add()
	 */
	public function add(Page $page, Page $parentPage = null)
	{
		$this->pages[] = $page;
		if ($parentPage)
		{
			$this->parentPages[$page->getUrl()] = $parentPage;
		}
	}

	/**
	 * @see Vidola\Document.PageList::getPages()
	 */
	public function getPages()
	{
		return $this->pages;
	}

	/**
	 * @see Vidola\Document.Structure::getPreviousPage()
	 */
	public function getPreviousPage(Page $page)
	{
		$pages = $this->getPages();
		$pageKey = array_search($page, $pages);
		if ($pageKey > 0)
		{
			return $pages[$pageKey-1];
		}

		return null;
	}

	/**
	 * @see Vidola\Document.Structure::getNextPage()
	 */
	public function getNextPage(Page $page)
	{
		$pages = $this->getPages();
		$pageKey = array_search($page, $pages);
		$pageKey++;
		if ($pageKey !== count($pages))
		{
			return $pages[$pageKey];
		}

		return null;
	}

	/**
	 * @see Vidola\Document.Structure::getStartPage()
	 */
	public function getStartPage()
	{
		if (isset($this->pages[0]))
		{
			return $this->pages[0];
		}

		return null;
	}

	/**
	 * @see Vidola\Document.Structure::getUrl()
	 */
	public function getUrl(Page $from, Linkable $to)
	{
		return $this->internalUrlBuilder->createRelativeLink($to->getUrl(), $from->getUrl());
	}

	/**
	 * @see Vidola\Document.Structure::getBreadCrumbs()
	 */
	public function getBreadCrumbs(Page $page)
	{
		$startPage = $this->getStartPage();
		$breadCrumbs = $this->getPagesThatLeadTo($startPage, $page);
		array_unshift($breadCrumbs, $startPage);
		if ($page != $startPage)
		{
			$breadCrumbs[] = $page;
		}

		return $breadCrumbs;
	}

	private function getPagesThatLeadTo(Page $from, Page $to)
	{
		if ($from == $to)
		{
			return array();
		}

		if (!isset($this->parentPages[$to->getUrl()]))
		{
			return array();
		}

		$parentPage = $this->parentPages[$to->getUrl()];
		if ($parentPage == $from)
		{
			return array();
		}

		$parentPages = $this->getPagesThatLeadTo($from, $parentPage);
		array_unshift($parentPages, $parentPage);

		return $parentPages;
	}

	/**
	 * @see Vidola\Document.FilenameCreator::createFilename()
	 */
	public function createFilename(Page $page)
	{
		$fileParts = pathinfo($page->getUrl());
		return $fileParts['dirname'] . DIRECTORY_SEPARATOR . $fileParts['filename'];
	}
}