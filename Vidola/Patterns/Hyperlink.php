<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

use Vidola\Util\RelativeUrlBuilder;

/**
 * @package Vidola
 */
class Hyperlink implements Pattern
{
	private $linkDefinitions;

	private $relativeUrlBuilder;

	public function __construct(
		LinkDefinitionCollector $linkDefinitionCollector,
		RelativeUrlBuilder $relativeUrlBuilder
	) {
		$this->linkDefinitions = $linkDefinitionCollector;
		$this->relativeUrlBuilder = $relativeUrlBuilder;
	}

	public function replace($text)
	{
		// possibilities:
		//
		// A) with link
		// [[anchor text http://url "title"]]
		// [[anchor text http://url]]
		// [[http://url]]
		// [[http://url "title"]]
		// [anchor text][link definition]
		$replaced = preg_replace_callback(
			// [[anchor link "title"]]
			"#\[\[(.+? )?([^\s\"]+)( \".+?\")?]\]#",
			array($this, 'replaceLink'),
			$text
		);

		// B) with link definition
		// link definition (see Pattern LinkDefinitionCollector)
		// ----------------
		// [link definition]: http://www.example.com "title"
		$replaced = preg_replace_callback(
			// [anchore][link def]
			"#\[(.+?)\] ?\[(.+?)\]#",
			array($this, 'replaceLinkDefinition'),
			$replaced
		);

		return $replaced;
	}

	private function replaceLink($regexMatch)
	{
		$url = $regexMatch[2];

		$anchorText = substr($regexMatch[1], 0, -1);
		if ($anchorText == '')
		{
			$anchorText = $url;
		}

		if (!isset($regexMatch[3]))
		{
			$title = null;
		}
		else
		{
			$title = substr($regexMatch[3], 2, -1);
		}

		return $this->createLink($title, $url, $anchorText);
	}

	private function replaceLinkDefinition($regexMatch)
	{
		$linkDef = $this->linkDefinitions->get($regexMatch[2]);

		$title = $linkDef->getTitle();
		$url = $linkDef->getUrl();
		$anchorText = $regexMatch[1];

		return $this->createLink($title, $url, $anchorText);
	}

	private function createLink($title, $url, $anchorText)
	{
		if ($title)
		{
			$titleAttr = $titleAttr = " title=\"$title\"";
		}
		else
		{
			$titleAttr = "";
		}

		if ($this->isRelative($url))
		{
			$url = $this->relativeUrlBuilder->buildUrl($url);
		}

		return '<a' . $titleAttr . ' href="' . $url . '">' . $anchorText . '</a>';
	}

	private function isRelative($url)
	{
		$filePart = strstr($url, "#", true);

		if (!$filePart)
		{
			$filePart = $url;
		}

		if (preg_match("#[^a-zA-Z0-9/]#", $filePart))
		{
			return false;
		}

		return true;
	}
}