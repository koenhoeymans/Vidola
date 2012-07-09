<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\Util\ContentRetriever;
use Vidola\Parser\Parser;
use Vidola\Util\SubfileDetector;
use Vidola\Util\InternalUrlBuilder;
use Vidola\Util\NameCreator;
use Vidola\Pattern\Patterns\TableOfContents;

/**
 * @package Vidola
 */
class MarkdownBasedDocument implements DocumentApiBuilder, DocumentStructure
{
	private $rootFile;

	private $contentRetriever;

	private $parser;

	private $subfileDetector;

	private $internalUrlBuilder;

	private $toc;

	private $nameCreator;

	/**
	 * Keeps Toc in memory so we don't need to build it again.
	 * 
	 * @var array array($file => $toc);
	 */
	private $tocCache = array();

	/**
	 * List of files in the project.
	 * 
	 * @var Array|null Null if not determined yet.
	 */
	private $fileList = null;

	/**
	 * @todo refactor
	 */
	public function __construct(
		$rootFile,
		ContentRetriever $contentRetriever,
		Parser $parser,
		SubfileDetector $subfileDetector,
		InternalUrlBuilder $internalUrlBuilder,
		TableOfContents $toc,
		NameCreator $nameCreator
	) {
		$this->rootFile = $rootFile;
		$this->contentRetriever = $contentRetriever;
		$this->parser = $parser;
		$this->subfileDetector = $subfileDetector;
		$this->internalUrlBuilder = $internalUrlBuilder;
		$this->toc = $toc;
		$this->nameCreator = $nameCreator;
	}

	/**
	 * @see Vidola\Document.DocumentApiBuilder::buildApi()
	 */
	public function buildApi($file)
	{
		return new \Vidola\Document\MarkdownBasedDocumentViewApi($this, $file);
	}

	public function getFileList()
	{
		if ($this->fileList)
		{
			return $this->fileList;
		}

		$subfiles = $this->getSubfilesRecursively($this->rootFile);
		array_unshift($subfiles, $this->rootFile);

		$this->fileList = $subfiles;

		return $subfiles;
	}

	private function getSubfilesRecursively($file)
	{
		$subfiles = $this->getSubfiles($file);
		foreach ($subfiles as $subfile)
		{
			$subsubfiles = $this->getSubfilesRecursively($subfile);
			$subfiles = array_merge($subfiles, $subsubfiles);
		}

		return array_unique($subfiles);
	}

	public function getSubfiles($file)
	{
		$text = $this->contentRetriever->retrieve($file);
		return $this->subfileDetector->getSubfiles($text);
	}

	public function getFilename($file)
	{
		$fileParts = pathinfo($file);
		return $fileParts['filename'];
	}

	public function getToc($file)
	{
		if (isset($this->tocCache[$file]))
		{
			return $this->tocCache[$file];
		}

		$this->tocCache[$file] = $this->toc->createTocNode(
			$this->contentRetriever->retrieve($file),
			new \DOMDocument()
		);

		return $this->tocCache[$file];
	}

	public function getContent($file)
	{
		return $this->parser->parse($this->contentRetriever->retrieve($file));
	}

	/**
	 * Get the previous page as written in the original document.
	 *
	 * @param string $page The page as in the original document.
	 */
	private function getPreviousPage($page)
	{
		$pageList = $this->getFileList();
		$pageKey = array_search($page, $pageList);
		if ($pageKey !== 0)
		{
			return $pageList[$pageKey-1];
		}
	
		return null;
	}

	/**
	 * Get the next page as written in the original document.
	 * 
	 * @param string $page The page as in the original document.
	 */
	private function getNextPage($page)
	{
		$pageList = $this->getFileList();
		$pageKey = array_search($page, $pageList);
		$pageKey++;
		if ($pageKey !== count($pageList))
		{
			return $pageList[$pageKey];
		}

		return null;
	}

	/**
	 * Get the name of a page from a given filename.
	 * 
	 * @param string $file
	 */
	public function getPageName($file)
	{
		return $this->nameCreator->getName($this->contentRetriever->retrieve($file));
	}

	public function getPreviousPageLink($page)
	{
		$nextPage = $this->getPreviousPage($page);

		if ($nextPage)
		{
			return $this->internalUrlBuilder->buildFrom($nextPage);
		}

		return null;
	}

	public function getPreviousPageName($file)
	{
		$previousPage = $this->getPreviousPage($file);

		if ($previousPage)
		{
			return $this->getPageName($previousPage);
		}

		return null;
	}

	public function getNextPageLink($file)
	{
		$nextPage = $this->getNextPage($file);
		
		if ($nextPage)
		{
			return $this->internalUrlBuilder->buildFrom($nextPage);
		}
		
		return null;
	}

	public function getNextPageName($file)
	{
		$nextPage = $this->getNextPage($file);

		if ($nextPage)
		{
			return $this->getPageName($this->getNextPage($file));
		}

		return null;
	}

	public function getStartPageLink()
	{
		return $this->internalUrlBuilder->buildFrom($this->rootFile);
	}

	public function getLink($page)
	{
		return $this->internalUrlBuilder->buildFrom($page);
	}

	/**
	 * @todo consider refactoring using documentStructure
	 * 
	 * A list of the pages that lead to `$page` as subpage.
	 * 
	 * @param string $page
	 * @return array
	 */
	public function getBreadCrumbs($page)
	{
		$breadCrumbs = $this->getPagesThatLeadTo($this->rootFile, $page);
		array_unshift($breadCrumbs, $this->rootFile);

		return $breadCrumbs;
	}

	private function getPagesThatLeadTo($startPage, $endPage)
	{
		$inBetweenPages = array();

		$subpages = $this->getSubfiles($startPage);
		foreach ($subpages as $subpage)
		{
			if ($subpage === $endPage)
			{
				$inBetweenPages[] = $subpage;
				break;
			}

			array_merge($inBetweenPages, $this->getPagesThatLeadTo($subpage, $endPage));
		}

		return $inBetweenPages;
	}
}