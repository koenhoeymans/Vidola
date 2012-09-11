<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;
use Vidola\Processor\Processors\LinkDefinitionCollector;

/**
 * @package Vidola
 */
class Image extends Pattern
{
	private $linkDefinitions;

	public function __construct(LinkDefinitionCollector $linkDefinitionCollector)
	{
		$this->linkDefinitions = $linkDefinitionCollector;
	}

	public function getRegex()
	{
		return
			'@

			(?<inline>(?J)
				!\[(?<alt>.*)\]							# ![alternate text]
				\(										# (
					(?<path>							# path|<path>
						<(\S+?)?>
						|
						(\S+?)?
					)
					([ ]+("|\')(?<title>.+)("|\')[ ]*)?	# "optional title"
				\)										# )
			)

			|

			(?<reference>(?J)
				(?<begin>^|[ ]+)
				!\[(?<alt>.+)\]					# ![alternate text]
				\[(?<id>.+)\]					# [id]
				(?<end>[ ]+|$)
			)
	
			@xU';
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		if (isset($match['reference']))
		{
			return $this->replaceReference($match, $ownerDocument);
		}
		else
		{
			return $this->replaceInline($match, $ownerDocument);
		}
	}

	private function replaceInline(array $match, \DOMDocument $domDoc)
	{
		$path = str_replace('"', '&quot;', $match['path']);
		if (isset($path[0]) && $path[0] === '<')
		{
			$path = substr($path, 1, -1);
		}

		$img = $domDoc->createElement('img');
		$img->setAttribute('alt', $match['alt']);
		if (isset($match['title']))
		{
			$img->setAttribute('title', $match['title']);
		}
		$img->setAttribute('src', $path);

		return $img;
	}

	/**
	 * @todo replace circular handling
	 */
	private function replaceReference(array $match, \DOMDocument $domDoc)
	{
		$linkDefinition = $this->linkDefinitions->get($match['id']);
		if (!$linkDefinition)
		{
			throw new \Exception('Following link definition not found: "['
			. $regexMatch['id'] . ']"'
			);
		}
		$title = $linkDefinition->getTitle();
			
		$img = $domDoc->createElement('img');
		$img->setAttribute('alt', $match['alt']);
		if ($title)
		{
			$img->setAttribute('title', $title);
		}
		$img->setAttribute('src', $linkDefinition->getUrl());

		return $img;
	}
}