<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use Vidola\Patterns\TableOfContents,
	Vidola\Util\FileRetriever;

/**
 * @package Vidola
 */
class DocumentStructure
{
	private $fileRetriever;

	private $toc;

	public function __construct(FileRetriever $fileRetriever, TableOfContents $toc)
	{
		$this->fileRetriever = $fileRetriever;
		$this->toc = $toc;
	}

	/**
	 * Get files listed in a table of contents.
	 * 
	 * @param string $fileName The name of the file as specified in the toc
	 */
	public function getSubFiles($fileName)
	{
		return $this->toc->recursivelyGetListOfIncludedFiles(
			$this->fileRetriever->retrieveContent($fileName)
		);
	}
}