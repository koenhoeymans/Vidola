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

	/**
	 * Recursively lists all subfiles contained in the table of contents.
	 * 
	 * @param unknown_type $text
	 */
	public function recursivelyGetListOfIncludedFiles($text)
	{
		$list = array();

		preg_match_all(self::TOC_REGEX, $text, $matches, PREG_PATTERN_ORDER);
		foreach ($matches[6] as $regexPartWithIncludeList)
		{
			$files = $this->recursivelyGetFilesToInclude($regexPartWithIncludeList);
			$list = array_merge($list, array_keys($files));
		}

		return $list;
	}

	public function replace($text)
	{
		return preg_replace_callback(
			self::TOC_REGEX,
			array($this, 'buildReplacement'),
			$text
		);
	}

	private function buildReplacement($regexmatch)
	{
		$options = $this->getOptions($regexmatch[3]);
		$maxDepth = isset($options['depth']) ? $options['depth'] : null;
		$fileList = $this->recursivelyGetFilesToInclude($regexmatch[6]);
		$textAfterToc = $regexmatch[9];
		$headerList = $this->getListOfHeaders($textAfterToc, $fileList);
		$toc = $this->buildToc($headerList, $maxDepth);

		return $toc . $textAfterToc;
	}

	private function recursivelyGetFilesToInclude($regexPartWithListOfFiles)
	{
		$fileList = array();

		$namesFromCurrentList = $this->getListFromVidolaText($regexPartWithListOfFiles);

		foreach ($namesFromCurrentList as $fileToInclude)
		{
			$textOfFile = $this->fileRetriever->retrieveContent($fileToInclude);
			$fileList[$fileToInclude] = $textOfFile;

			preg_match_all(
				self::TOC_REGEX, $textOfFile, $tocBlocks, PREG_SET_ORDER
			);

			foreach ($tocBlocks as $toc)
			{
				$subFileList = $this->recursivelyGetFilesToInclude($toc[6]);
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
			$list .= "\n\t<li>\n\t\t<a href=\"$file#$ref\">$title</a>";

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