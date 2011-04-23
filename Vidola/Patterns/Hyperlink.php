<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Hyperlink implements Pattern
{
	private $linkDefinitions;

	public function __construct(LinkDefinitionCollector $linkDefinitionCollector)
	{
		$this->linkDefinitions = $linkDefinitionCollector;
	}

	public function replace($text)
	{
		// possibilities:
		// --------------
		// [anchor text][http://url "title"]
		// [anchor text][http://url]
		// [anchor text][link def]
		// [http://url]
		// [http://url "title"]
		//
		// link definition (see Pattern LinkDefinitionCollector)
		// ----------------
		// [link def]: http://www.example.com "title"
		$linkDefinitions = $this->linkDefinitions;
		return preg_replace_callback(
			"#\[.+\]( )?\[.+\]|\[http://.+\]#U",
			function ($match) use ($linkDefinitions)
			{
				if (preg_match("#\[.+?\]\[(\http://[^\s\]]+)\]#", $match[0], $matches))
				{
					$url = $matches[1];
				}
				elseif (preg_match("#\[(http://[^\s\]]+)#", $match[0], $matches))
				{
					$url = $matches[1];
				}
				else
				{
					preg_match("#\[(.+?)\]( )?\[(.+)\]#", $match[0], $matches);
					$linkDefinitionName = $matches[3];
					$linkDefinition = $linkDefinitions->get($linkDefinitionName);
					$url = $linkDefinition->getUrl();
				}

				if (preg_match("#\".+\"#U", $match[0], $matches))
				{
					$title = 'title=' . $matches[0] . ' ';
				}
				else
				{
					$title = null;
					if (isset($linkDefinition))
					{
						$title = $linkDefinition->getTitle();
					}
					$title = ($title === null) ? '' : 'title="' . $title . '" ';
				}

				if (preg_match('#\[(.+)\](( )?)\[.+\]#U', $match[0], $matches))
				{
					$anchorText = $matches[1];
				}
				else
				{
					$anchorText = $url;
				}

				return '<a ' . $title . 'href="' . $url . '">' . $anchorText . '</a>';
			},
			$text
		);
	}
}