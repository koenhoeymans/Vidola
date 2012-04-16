<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use Vidola\Pattern\Patterns\TableOfContents;
use Vidola\Util\DocFileRetriever;

/**
 * @package Vidola
 */
class DocumentStructure
{
	private $fileRetriever;

	private $toc;

	private $subfiles = array();

	public function __construct(DocFileRetriever $fileRetriever, TableOfContents $toc)
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
		if (isset($this->subfiles[$fileName]))
		{
			return $this->subfiles[$fileName];
		}

		$files = $this->toc->recursivelyGetListOfIncludedFiles(
			$this->fileRetriever->retrieveContent($fileName)
		);

		$this->subfiles[$fileName] = $files;

		return $files;
	}

	/**
	 * Get the previous file of the document.
	 * 
	 * @param string $fileName
	 * @return array|false
	 */
	public function getPreviousFile($fileName)
	{
		foreach ($this->subfiles as $file => $subfiles)
		{
			foreach ($subfiles as $key => $subfile)
			{
				if ($subfile === $fileName)
				{
					if ($key !== 0)
					{
						return $subfiles[$key-1];
					}
					else
					{
						return $file;
					}
				}
			}
		}

		return null;
	}

	/**
	 * Get the next file of the document.
	 * 
	 * @param string $fileName
	 * @return array|false
	 */
	public function getNextFile($fileName)
	{
		$subfiles = $this->getSubFiles($fileName);

		if (isset($subfiles[0]))
		{
			return $subfiles[0];
		}

		foreach ($this->subfiles as $file => $subfiles)
		{
			foreach ($subfiles as $key => $subfile)
			{
				if (($subfile === $fileName) && (isset($subfiles[$key+1])))
				{
					return $subfiles[$key+1];
				}
			}
		}

		return null;
	}
}