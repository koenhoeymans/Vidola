<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

use Vidola\Services\FileRetriever;
use Vidola\Patterns\TableOfContents\HeaderFinder;

/**
 * @package Vidola
 */
class TableOfContents implements Pattern
{
	const TOC_REGEX = "#(?<=\n\n|^\n|^)((\t| )*)table of contents:((\n\\1(\t| ).+)*)(\n(\n\\1(\t| ).+)+)?(\n\n(?!\\1(\t| ))(.|\n)+|$)#";

	private $headerFinder;

	private $fileRetriever;

	public function __construct(
		HeaderFinder $headerFinder,
		FileRetriever $fileRetriever
	) {
		$this->headerFinder = $headerFinder;
		$this->fileRetriever = $fileRetriever;
	}

	public function replace($text)
	{
		return preg_replace_callback(
			self::TOC_REGEX,
			array($this, 'buildReplacement'),
			$text
		);
	}

	private function buildReplacement($match)
	{
		$options = $this->getOptions($match[3]);
		$maxDepth = isset($options['depth']) ? $options['depth'] : null;
		$listOfSubtexts = $this->recursivelyGetListOfFilesToInclude($match[6]);
		$textAfterToc = $match[9];

		$headerList = $this->getListOfHeaders($textAfterToc, $listOfSubtexts);
//var_dump($headerList);
		$toc = $this->buildToc($headerList, $maxDepth);
//var_dump($toc);
//var_dump($this->headerFinder);
		return $toc . $textAfterToc;
	}

	private function recursivelyGetListOfFilesToInclude($text)
	{
		$inclusionList = $this->getListFromVidolaText($text);

		foreach ($inclusionList as $fileToInclude)
		{
			$textOfFile = $this->fileRetriever->retrieveContent(
				ucfirst($fileToInclude) . '.vi'
			);
			preg_match_all(
				self::TOC_REGEX, $textOfFile, $tocBlocks, PREG_SET_ORDER
			);
			foreach ($tocBlocks as $toc)
			{
				$filesToInclude = $this->recursivelyGetListOfFilesToInclude($toc[6]);
				$inclusionList = array_merge($inclusionList, $filesToInclude);
			}
		}

		return $inclusionList;
	}

	private function getListOfHeaders($textAfterToc, $listOfSubTexts)
	{
		$headers = $this->headerFinder->getHeadersSequentially($textAfterToc);

		foreach ($listOfSubTexts as $subTextFileName)
		{
			$subText = $this->fileRetriever->retrieveContent(
				ucfirst($subTextFileName) . '.vi'
			);
			$headers = array_merge(
				$headers,
				$this->headerFinder->getHeadersSequentially($subText)
			);
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

	private function buildToc(array $headers, $maxDepth = null)
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
				return '';
			}
		}

		$list = "<ul>";
		$listLevel = null;

		foreach ($headers as $key => $header)
		{
			unset($headers[$key]);

			$level = $header['level'];
			$title = $header['title'];

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
			$list .= "\n\t<li>\n\t\t<a href=\"#$ref\">$title</a>";

			if (isset($headers[$key+1]))
			{
				if ($headers[$key+1]['level'] > $level)
				{
					$depth++;
					$sublist = $this->buildToc($headers, $maxDepth);
					if ($sublist !== '')
					{
						$list .= "\n$sublist";
					}
					$depth--;
				}
			}

			$list .= "\n\t</li>";
		}

		$list .= "\n</ul>";

		return $list;
	}

	private function getOptions($text)
	{
		$options = array();

		preg_match_all("#\n(\t| )+(.+?)(?=\n|$)#", $text, $matches);
		foreach ($matches[2] as $line)
		{
			$option = explode(':', $line);
			$options[$option[0]] = trim($option[1]);
		}

		return $options;
	}
}