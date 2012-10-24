<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\Parser\Parser;
use Vidola\Util\ContentRetriever;
use Vidola\Processor\TextProcessor;
use Vidola\Util\TitleCreator;
use Vidola\Util\InternalUrlBuilder;
use Vidola\Pattern\Patterns\TableOfContents;

/**
 * @package Vidola
 */
class MarkdownBasedDocumentation implements DocumentationApiBuilder, FilenameCreator, Structure
{
	private $rootFile;

	private $contentRetriever;

	private $parser;

	private $titleCreator;

	private $internalUrlBuilder;

	private $rawCache = array();

	private $parsedCache = array();

	private $toc;

	private $postTextProcessors = array();

	private $pages = null;

	public function __construct(
		$rootFile,
		ContentRetriever $contentRetriever,
		Parser $parser,
		TitleCreator $titleCreator,
		InternalUrlBuilder $internalUrlBuilder,
		TableOfContents $toc
	) {
		$this->rootFile = $rootFile;
		$this->contentRetriever = $contentRetriever;
		$this->parser = $parser;
		$this->titleCreator = $titleCreator;
		$this->internalUrlBuilder = $internalUrlBuilder;
		$this->toc = $toc;
	}

	/**
	 * @see Vidola\Document.DocumentApiBuilder::buildApi()
	 */
	public function buildApi($file)
	{
		return new \Vidola\Document\MarkdownBasedDocumentationViewApi($this, $file);
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

	/**
  	 * Get content parsed by the different patterns.
	 *
	 * @param string $page
	 * @param bool $dom
	 * @return \DomDocument|string
	 */
	public function getParsedContent($page, $dom = false)
	{
		if (isset($this->parsedCache[$page]))
		{
			$content = $this->parsedCache[$page];
		}
		else
		{
			$content = $this->getRawContent($page);
			$content = $this->parser->parse($content);
			$this->parsedCache[$page] = $content;
		}

		if (!$dom)
		{
			$content = $content->saveXml($content->documentElement);

			# DomDocument::saveXml encodes entities like `&` when added within
			# a text node.
			$content = str_replace(
				array('&amp;amp;', '&amp;copy;', '&amp;quot;', '&amp;#'),
				array('&amp;', '&copy;', '&quot;', '&#'),
				$content
			);

			$content = $this->postProcess($content);
		}

		return $content;
	}
	
	/**
	 * Get content as in file.
	 *
	 * @param string $page
	 * @return string
	 */
	public function getRawContent($page)
	{
		if (isset($this->rawCache[$page]))
		{
			return $this->rawCache[$page];
		}

		$content = $this->contentRetriever->retrieve($page);
		$this->rawCache[$page] = $content;

		return $content;
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
	 * Get the title of a page.
	 *
	 * @param string $page
	 * @return string
	 */
	public function getPageTitle($page)
	{
		return $this->titleCreator->createPageTitle(
			$this->contentRetriever->retrieve($page), $page
		);
	}

	/**
	 * Get a link to an internal resource relative to another resource if specified.
	 *
	 * @param string $page
	 * @param string $relativeTo
	 * @return string
	 */
	public function getLink($page, $relativeTo = null)
	{
		return $this->internalUrlBuilder->createRelativeLink($page, $relativeTo);
	}

	/**
	 * Get the name of the first page.
	 * 
	 * @return string
	 */
	public function getStartPage()
	{
		return $this->rootFile;
	}

	/**
	 * A list of all pages that are referred to in the specified page in
	 * a table of contents.
	 * 
	 * @param string $page
	 * @return array
	 */
	public function getSubpages($page)
	{
		$text = $this->contentRetriever->retrieve($page);
		return $this->toc->getSubpages($text);
	}

	/**
	 * Get the previous page.
	 *
	 * @param string $page
	 * @return string|null
	 */
	public function getPreviousPage($page)
	{
		$pageList = $this->getPages();
		$pageKey = array_search($page, $pageList);
		if ($pageKey > 0)
		{
			return $pageList[$pageKey-1];
		}

		return null;
	}

	/**
	 * Get the next page.
	 *
	 * @param string $file
	 * @return string|null
	 */
	public function getNextPage($page)
	{
		$pageList = $this->getPages();
		$pageKey = array_search($page, $pageList);
		$pageKey++;
		if ($pageKey !== count($pageList))
		{
			return $pageList[$pageKey];
		}

		return null;
	}

	/**
	 * A list of all pages in order of appearance in the individual TOC.
	 * 
	 * @return array
	 */
	public function getPages()
	{
		if ($this->pages)
		{
			return $this->pages;
		}

		$pages = $this->getSubpagesRecursively($this->rootFile);
		array_unshift($pages, $this->rootFile);

		$this->pages = $pages;

		return $pages;
	}

	private function getSubpagesRecursively($file)
	{
		$subpages = $this->getSubpages($file);
		foreach ($subpages as $subpage)
		{
			$subsubpages = $this->getSubpagesRecursively($subpage);
			$subpages = array_merge($subpages, $subsubpages);
		}

		return array_unique($subpages);
	}

	/**
	 * @see Vidola\Document.FilenameCreator::createFilename()
	 */
	public function createFilename($file)
	{
		$fileParts = pathinfo($file);
		return $fileParts['dirname'] . DIRECTORY_SEPARATOR . $fileParts['filename'];
	}

	/**
	 * Creates a DomElement containing the ToC of a page.
	 *
	 * @param string $page
	 * @return \DomElement|null
	 */
	public function getToc($page)
	{
		if (isset($this->tocCache[$page]))
		{
			return $this->tocCache[$page];
		}

		$toc = $this->createTocNode($this->getParsedContent($page, true));
		$this->tocCache[$page] = $toc;

		return $toc;
	}

	private function createTocNode(\DomDocument $domDoc)
	{
		$headers = array();
		$xpath = new \DOMXPath($domDoc);
		// @todo should be html agnostic
		$headerNodes = $xpath->query('//h1|h2|h3|h4|h5|h6');
		foreach ($headerNodes as $headerNode)
		{
			$headers[] = array(
					'id' => $headerNode->getAttribute('id'),
					'level' => $headerNode->nodeName[1],
					'title' => $headerNode->nodeValue
			);
		}
		return $this->toc->buildToc($headers, null, $domDoc);
	}

	/**
	 * A list of the files that lead to `$file` as subfile.
	 *
	 * @param string $file
	 * @return array
	 */
	public function getBreadCrumbs($file)
	{
		$breadCrumbs = $this->getFilesThatLeadTo($this->rootFile, $file);
		array_unshift($breadCrumbs, $this->rootFile);

		return $breadCrumbs;
	}

	private function getFilesThatLeadTo($startpage, $endpage)
	{
		$inBetweenPages = array();

		$subpages = $this->getSubpages($startpage);
		foreach ($subpages as $subpage)
		{
			if ($subpage === $endpage)
			{
				$inBetweenPages[] = $subpage;
				break;
			}

			array_merge($inBetweenPages, $this->getFilesThatLeadTo($subpage, $endpage));
		}

		return $inBetweenPages;
	}
}