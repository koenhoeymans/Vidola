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
		if (!preg_match("#(?<=\n\n|^)(\s*)table of contents:((\n\(.+?\))*)(((\n|.)+)$)#", $text))
		{
			return $text;
		}

		return preg_replace_callback(
			"#(?<=\n\n|^)((\t| )*)table of contents:((\n\\1(\t| )+.+)*)(\n\n(.|\n)+)$#",
			array($this, 'createToc'),
			$text
		);
	}

	private function createToc($match)
	{
		$options = $this->getOptions($match[3]);
		$text = $match[6];
		$headers = $this->headerFinder->getHeadersSequentially($text);
		$headerList = $this->buildHeaderList($headers, $options);

		return $headerList . $text;
	}

	private function buildHeaderList(array $headers, array $options)
	{
		static $depth;

		if (!isset($depth))
		{
			$depth = 1;
		}
		if (isset($options['depth']))
		{
			if ($depth > $options['depth'])
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
					$sublist = $this->buildHeaderList($headers, $options);
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