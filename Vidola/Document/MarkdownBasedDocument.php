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

	/**
	 * Return name of file without extension and relative to the rootfile. Eg if
	 * the rootfile is 'index' then a fileName could be 'subfolder/subfile'.
	 * 
	 * @param string $file
	 */
	public function getFileName($file)
	{
		$fileParts = pathinfo($file);
		return $fileParts['dirname'] . DIRECTORY_SEPARATOR . $fileParts['filename'];
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
	 * Get the previous file as written in the original document.
	 *
	 * @param string $file The file reference as in the original document.
	 */
	private function getPreviousFileName($file)
	{
		$fileList = $this->getFileList();
		$fileKey = array_search($file, $fileList);
		if ($fileKey !== 0)
		{
			return $fileList[$fileKey-1];
		}
	
		return null;
	}

	/**
	 * Get the next file as written in the original document.
	 * 
	 * @param string $file The file as in the original document.
	 */
	private function getNextFileName($file)
	{
		$fileList = $this->getFileList();
		$fileKey = array_search($file, $fileList);
		$fileKey++;
		if ($fileKey !== count($fileList))
		{
			return $fileList[$fileKey];
		}

		return null;
	}

	/**
	 * Get the name of a file from a given filename.
	 * 
	 * @param string $file
	 */
	public function getFileTitle($file)
	{
		return $this->nameCreator->getTitle($this->contentRetriever->retrieve($file));
	}

	public function getPreviousFileLink($file)
	{
		$prevFile = $this->getPreviousFileName($file);

		if ($prevFile)
		{
			$depthFile = count(explode(DIRECTORY_SEPARATOR, $file)) -1;
			$depthPrevFile = count(explode(DIRECTORY_SEPARATOR, $prevFile)) -1;
			$depthDifference = $depthPrevFile-$depthFile;

			$prevFile = $this->internalUrlBuilder->buildFrom($prevFile);
			for($depthDifference; $depthDifference<0; $depthDifference++)
			{
				$prevFile = '../' . $prevFile;
			}

			return $prevFile;
		}

		return null;
	}

	public function getPreviousFileTitle($file)
	{
		$previousFile = $this->getPreviousFileName($file);

		if ($previousFile)
		{
			return $this->getFileTitle($previousFile);
		}

		return null;
	}

	public function getNextFileLink($file)
	{
		$nextFile = $this->getNextFileName($file);
		
		if ($nextFile)
		{
			return $this->internalUrlBuilder->buildFrom($nextFile);
		}
		
		return null;
	}

	public function getNextFileTitle($file)
	{
		$nextFile = $this->getNextFileName($file);

		if ($nextFile)
		{
			return $this->getFileTitle($nextFile);
		}

		return null;
	}

	public function getStartFileLink()
	{
		return $this->internalUrlBuilder->buildFrom($this->rootFile);
	}

	public function getLink($file)
	{
		return $this->internalUrlBuilder->buildFrom($file);
	}

	/**
	 * @todo consider refactoring using documentStructure
	 * 
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

	private function getFilesThatLeadTo($startFile, $endFile)
	{
		$inBetweenFiles = array();

		$subfiles = $this->getSubfiles($startFile);
		foreach ($subfiles as $subfile)
		{
			if ($subfile === $endFile)
			{
				$inBetweenFiles[] = $subfile;
				break;
			}

			array_merge($inBetweenFiles, $this->getFilesThatLeadTo($subfile, $endFile));
		}

		return $inBetweenFiles;
	}
}