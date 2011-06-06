<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use \Vidola\Services\FileRetriever;

/**
 * @package Vidola
 */
class DocumentStructure
{
	private $fileRetriever;

	public function __construct(FileRetriever $fileRetriever)
	{
		$this->fileRetriever = $fileRetriever;
	}

	public function getSubFiles($text)
	{
		$subfiles = array();

		/**
		 * @todo remove this hidden dependency
		 */
		preg_match_all(
			\Vidola\Patterns\TableOfContents::TOC_REGEX, $text, $tocBlocks, PREG_SET_ORDER
		);

		foreach ($tocBlocks as $tocBlock)
		{
			$filesToInclude = $this->getListFromVidolaText($tocBlock[6]);
			foreach ($filesToInclude as $fileToInclude)
			{
				if (!in_array($filesToInclude, $subfiles))
				{
					$subfile = ucfirst($fileToInclude . '.vi');
					$subfiles[] = $subfile;
					$subfileText = $this->fileRetriever->retrieveContent($subfile);
					$inclusionList = array_merge(
						$subfiles,
						$this->getSubFiles($subfileText)
					);
				}
			}
		}

		return $subfiles;
	}

	private function getListFromVidolaText($text)
	{
		$inclusionList = array();

		$lines = explode("\n", $text);
		foreach ($lines as $include)
		{
			if ($include !== '')
			{
				$inclusionList[] = trim($include);
			}
		}

		return $inclusionList;
	}
}