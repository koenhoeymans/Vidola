<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\Util\InternalUrlBuilder;
use Vidola\Processor\TextProcessor;
use Vidola\Parser\Parser;
use Vidola\Util\TitleCreator;
use Vidola\Util\TocGenerator;

/**
 * @package Vidola
 */
class MarkdownBasedDocumentation implements DocumentationApiBuilder, FilenameCreator, PageGuide, Structure
{
	private $postTextProcessors = array();

	private $content = array();

	private $parser;

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
		Parser $parser,
		TitleCreator $titleCreator,
		TocGenerator $tocGenerator,
		InternalUrlBuilder $internalUrlBuilder
	) {
		$this->parser = $parser;
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
		# and for \Vidola\Pattern\Patterns\Header to assign another unique id
		$id = $page->getUrl();
		if (isset($this->content[$id]))
		{
			$content = $this->content[$id];
		}
		else
		{
			$content = $page->getRawContent();
			$content = $this->parser->parse($content);
			$this->content[$id] = $content;
		}

		if ($dom)
		{
			return $content;
		}

		$content = $content->saveXml($content->documentElement);

		# DomDocument::saveXml encodes entities like `&` when added within
		# a text node.
		$content = str_replace(
		array('&amp;amp;', '&amp;copy;', '&amp;quot;', '&amp;#'),
		array('&amp;', '&copy;', '&quot;', '&#'),
		$content
		);

		$content = $this->postProcess($content);

		return $content;
	}

	/**
	 * Add a processor that is called before the content is returned, after
	 * all parsing is done.
	 *
	 * @param TextProcessor $processor
	 */
	public function addPostTextProcessor(TextProcessor $processor)
	{
		$this->postTextProcessors[] = $processor;
	}

	private function postProcess($text)
	{
		foreach ($this->postTextProcessors as $processor)
		{
			$text = $processor->process($text);
		}

		return $text;
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
	public function getToc(Page $page)
	{
		return $this->tocGenerator->createTocNode($this->getParsedContent($page, true));
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