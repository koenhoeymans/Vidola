<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Util\DocFileRetriever;
use Vidola\Pattern\Patterns\TableOfContents\HeaderFinder;
use Vidola\Pattern\Pattern;
use Vidola\Document\Element;

/**
 * @package Vidola
 */
class TableOfContents extends Pattern
{
	private $headerFinder;

	private $docFileRetriever;

	public function __construct(
		HeaderFinder $headerFinder,
		DocFileRetriever $docFileRetriever
	) {
		$this->headerFinder = $headerFinder;
		$this->docFileRetriever = $docFileRetriever;
	}

	/**
	 * Recursively lists all subfiles contained in the table of contents.
	 * 
	 * @param unknown_type $text
	 */
	public function recursivelyGetListOfIncludedFiles($text)
	{
		$list = array();

		preg_match_all($this->getRegex(), $text, $matches, PREG_PATTERN_ORDER);
		foreach ($matches['pages'] as $regexPartWithIncludeList)
		{
			$files = $this->recursivelyGetFilesToInclude($regexPartWithIncludeList);
			$list = array_merge($list, array_keys($files));
		}

		return $list;
	}

	public function getRegex()
	{
		return
			'@
			(?<=\n\n|\n^|^)
 			{table\ of\ contents} 
 			(?<options>
				(
				\n
					((\t+|[ ]{4,}).+)*
				)?
			)
			(?<pages>
				(
				\n
					(\n(\t+|[ ]{4,}).+)+
				)?
			)
			(?=(?<text>\n\n[\S\s]*|$))
			@x';
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		return $this->buildReplacement($match, $parentNode);
	}

	private function buildReplacement(array $regexmatch, \DOMNode $parentNode)
	{
		$options = $this->getOptions($regexmatch['options']);
		$maxDepth = isset($options['depth']) ? $options['depth'] : null;

		$fileList = $this->recursivelyGetFilesToInclude($regexmatch['pages']);
		$textAfterToc = $regexmatch['text'];
		$headerList = $this->getListOfHeaders($textAfterToc, $fileList);
		return $this->buildToc($headerList, $maxDepth, $parentNode);
	}

	/**
	 * Builds array fileName => contents
	 */
	private function recursivelyGetFilesToInclude($regexPartWithListOfFiles)
	{
		$fileList = array();

		$namesFromCurrentList = $this->getListFromVidolaText($regexPartWithListOfFiles);

		foreach ($namesFromCurrentList as $fileToInclude)
		{
			$textOfFile = $this->docFileRetriever->retrieveContent($fileToInclude);
			$fileList[$fileToInclude] = $textOfFile;

			preg_match_all(
				$this->getRegex(), $textOfFile, $tocBlocks, PREG_SET_ORDER
			);

			foreach ($tocBlocks as $toc)
			{
				$subFileList = $this->recursivelyGetFilesToInclude($toc['pages']);
				$fileList = array_merge($fileList, $subFileList);
			}
		}

		return $fileList;
	}

	private function getListOfHeaders($textAfterToc, $fileList)
	{
		$headers = $this->headerFinder->getHeadersSequentially($textAfterToc);

		foreach ($fileList as $fileName => $contents)
		{
			$subTextHeaders = $this->headerFinder->getHeadersSequentially($contents);
			foreach ($subTextHeaders as $subTextHeader)
			{
				$subTextHeader['file'] = ucfirst($fileName) . '.html';
				$headers = array_merge(
					$headers,
					array($subTextHeader)
				);
			}
		}

		return $headers;
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

	private function buildToc(array $headers, $maxDepth = null, \DOMNode $parentNode)
	{
		if (empty($headers))
		{
			return '';
		}

		static $depth;

		if (!isset($depth))
		{
			$depth = 1;
		}
		if ($maxDepth)
		{
			if ($depth > $maxDepth)
			{
				return null;
			}
		}

		$ul = $this->getOwnerDocument($parentNode)->createElement('ul');

		$listLevel = null;

		foreach ($headers as $key => $header)
		{
			unset($headers[$key]);

			$level = $header['level'];
			$title = $header['title'];
			$file = isset($header['file']) ? $header['file'] : '';

			if (!$listLevel)
			{
				$listLevel = $level;
			}
			elseif ($level < $listLevel)
			{
				break;
			}
			elseif ($level > $listLevel)
			{
				continue;
			}

			$ref = str_replace(' ', '_', $title);

			$li = $this->getOwnerDocument($parentNode)->createElement('li');
			$ul->appendChild($li);
			$a = $this->getOwnerDocument($parentNode)->createElement('a', $title);
			$li->appendChild($a);
			$a->setAttribute('href', $file . '#' . $ref);

			if (isset($headers[$key+1]))
			{
				if ($headers[$key+1]['level'] > $level)
				{
					$depth++;
					$subUl = $this->buildToc($headers, $maxDepth, $li);
					if ($subUl)
					{
						$li->appendChild($subUl);
					}
					$depth--;
				}
			}
		}

		return $ul;
	}

	private function getOptions($text)
	{
		$options = array();

		preg_match_all("#($|\n)(\t| )*(.+?)(?=\n|$)#", $text, $matches);
		foreach ($matches[3] as $line)
		{
			$option = explode(':', $line);
			$options[$option[0]] = trim($option[1]);
		}

		return $options;
	}
}