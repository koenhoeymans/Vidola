<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\Util\ContentRetriever;
use Vidola\Parser\Parser;
use Vidola\Util\SubfileDetector;

/**
 * @package Vidola
 */
class MarkdownBasedDocument implements DocumentApiBuilder, DocumentStructure
{
	private $rootFile;

	private $contentRetriever;

	private $parser;

	private $subfileDetector;

	public function __construct(
		$rootFile,
		ContentRetriever $contentRetriever,
		Parser $parser,
		SubfileDetector $subfileDetector
	) {
		$this->rootFile = $rootFile;
		$this->contentRetriever = $contentRetriever;
		$this->parser = $parser;
		$this->subfileDetector = $subfileDetector;
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
		$subfiles = $this->getSubfilesRecursively($this->rootFile);
		array_unshift($subfiles, $this->rootFile);
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

	public function getContent($file)
	{
		return $this->parser->parse($this->contentRetriever->retrieve($file));
	}

	// @todo create title in first place from top header
	public function getTitle($file)
	{
		return str_replace(DIRECTORY_SEPARATOR, ' ', $file);
	}

	public function getPreviousPageName($file)
	{
		return 'previous title';
	}

	public function getNextPageName($file)
	{
		return 'next title';
	}
}