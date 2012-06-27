<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Util\ContentRetriever;
use Vidola\Pattern\Patterns\TableOfContents\HeaderFinder;
use Vidola\Util\InternalUrlBuilder;
use Vidola\Pattern\Pattern;
use Vidola\Document\Element;
use Vidola\Util\SubfileDetector;

/**
 * @package Vidola
 */
class TableOfContents extends Pattern implements SubfileDetector
{
	private $headerFinder;

	private $contentRetriever;

	private $internalUrlBuilder;

	public function __construct(
		HeaderFinder $headerFinder,
		ContentRetriever $contentRetriever,
		InternalUrlBuilder $internalUrlBuilder
	) {
		$this->headerFinder = $headerFinder;
		$this->contentRetriever = $contentRetriever;
		$this->internalUrlBuilder = $internalUrlBuilder;
	}

	/**
	 * @see Vidola\Pattern.Pattern::getRegex()
	 */
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

	/**
	 * @param string $text
	 * @return \DomElement|null
	 */
	public function createTocNode($text, \DomDocument $domDoc)
	{
		$headers = $this->headerFinder->getHeadersSequentially($text);
		$toc = $this->buildToc($headers, null, $domDoc);

		return $toc;
	}

	/**
	 * @see Vidola\Util.SubfileDetector::getSubfiles()
	 */
	public function getSubfiles($text)
	{
		$pageList = array();
		preg_match_all($this->getRegex(), $text, $matches, PREG_PATTERN_ORDER);
		foreach ($matches['pages'] as $pages)
		{
			$matches = $this->getListFromVidolaText($pages);
			$pageList = array_merge($pageList, $matches);
		}

		return $pageList;
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

	private function recursivelyGetFilesToInclude($regexPartWithListOfFiles)
	{
		$fileList = array();

		$namesFromCurrentList = $this->getListFromVidolaText($regexPartWithListOfFiles);

		foreach ($namesFromCurrentList as $fileToInclude)
		{
			$textOfFile = $this->contentRetriever->retrieve($fileToInclude);
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
				$subTextHeader['file'] = ucfirst($fileName);
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

	// @todo not logical that empty headers array can return ''.
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
			$ref = $header['id'];
			$file = isset($header['file']) ?
				$this->internalUrlBuilder->buildFrom($header['file']) :
				'';

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