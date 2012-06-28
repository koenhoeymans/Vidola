<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\Util\ContentRetriever;
use Vidola\Parser\Parser;
use Vidola\Util\SubfileDetector;
use Vidola\Util\InternalUrlBuilder;
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

	/**
	 * List of files in the project.
	 * 
	 * @var Array|null Null if not determined yet.
	 */
	private $fileList = null;

	public function __construct(
		$rootFile,
		ContentRetriever $contentRetriever,
		Parser $parser,
		SubfileDetector $subfileDetector,
		InternalUrlBuilder $internalUrlBuilder,
		TableOfContents $toc
	) {
		$this->rootFile = $rootFile;
		$this->contentRetriever = $contentRetriever;
		$this->parser = $parser;
		$this->subfileDetector = $subfileDetector;
		$this->internalUrlBuilder = $internalUrlBuilder;
		$this->toc = $toc;
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
		return $this->toc->createTocNode(
			$this->contentRetriever->retrieve($file),
			new \DOMDocument()
		);
	}

	public function getContent($file)
	{
		return $this->parser->parse($this->contentRetriever->retrieve($file));
	}

	// @todo create title in first place from top header
	// fall back to page name but add space between camelcase?? => see other libs
	public function getPageName($file)
	{
		return str_replace(DIRECTORY_SEPARATOR, ' ', $file);
	}

	public function getPreviousPageLink($file)
	{
		$fileList = $this->getFileList();
		$fileKey = array_search($file, $fileList);
		if ($fileKey !== 0)
		{
			return $this->internalUrlBuilder->buildFrom($fileList[$fileKey-1]);
		}

		return null;
	}

	public function getPreviousPageName($file)
	{
		return $this->getPageName($this->getPreviousPageLink($file));
	}

	public function getNextPageLink($file)
	{
		$fileList = $this->getFileList();
		$fileKey = array_search($file, $fileList);
		$fileKey++;
		if ($fileKey !== count($fileList))
		{
			return $this->internalUrlBuilder->buildFrom($fileList[$fileKey]);
		}

		return null;
	}

	public function getNextPageName($file)
	{
		return $this->getPageName($this->getNextPageLink($file));
	}

	public function getStartPageLink()
	{
		return $this->internalUrlBuilder->buildFrom($this->rootFile);
	}
}