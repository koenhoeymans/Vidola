<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class TableOfContents implements Pattern
{
	private $headerFinder;

	public function __construct(HeaderFinder $headerFinder)
	{
		$this->headerFinder = $headerFinder;
	}

	public function replace($text)
	{
		if (!preg_match("#(\n|^)(\s*)table of contents:((\n\(.+?\))*)((\n|.)+)(?=\n\n(?!\\3)|$)#", $text))
		{
			return $text;
		}

		return preg_replace_callback(
			"#(\n|^)(\s*)table of contents:((\n{1,2}\\2((\t| )+).+)*)((.|\n)+)#",
			array($this, 'createToc'),
			$text
		);
	}

	private function createToc($match)
	{
		$text = $match[7];
		$headers = $this->headerFinder->getHeadersSequentially($text);
		$headerList = $this->buildHeaderList($headers);

		return $match[1] . $headerList . $text;
	}

	private function buildHeaderList(array $headers)
	{
		$list = "<ul>\n";
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
			$list .= "\t<li>\n\t\t<a href=\"#$ref\">$title</a>\n";

			if (isset($headers[$key+1]))
			{
				if ($headers[$key+1]['level'] > $level)
				{
					$list .= $this->buildHeaderList($headers);
				}
			}

			$list .= "\t</li>\n";
		}

		$list .= "</ul>\n";

		return $list;
	}
	
	private function getOptions($text)
	{
		$options = array();

		preg_match_all("#\n\s*\((.+?)\)#", $text, $matches);
		foreach ($matches[1] as $line)
		{
			$option = explode(':', $line);
			$options[$option[0]] = trim($option[1]);
		}

		return $options;
	}

//	private function getPageList($text)
//	{
//		preg_match_all("#.+(?=\n|$)#", $text, $matches);
//		return $matches;
//	}

//	private function createLinkList($pageList)
//	{
//		$list = '<ul>';
//		foreach ($pageList as $page)
//		{
//			$list .= "\n<li>" . $page . '</li>';
//		}
//
//		return $list . "\n</ul>";
//	}
}