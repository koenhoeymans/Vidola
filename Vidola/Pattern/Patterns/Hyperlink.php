<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Util\RelativeUrlBuilder;
use Vidola\Processor\Processors\LinkDefinitionCollector;
use Vidola\Pattern\Pattern;

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
		$replaced = preg_replace_callback(
			'@
			\[(?<anchor>					# anchor text
				(\[(?2)*?\].*?|.+?)			
			)\]
			\(
			(<(?<url1>\S+)>|(?<url2>\S+))	# url or <url>
			(								# title
			[ ]									# space
			(?<quotes>"|\')						# single or double quotes
			(?<title>.+?)						# title text
			\g{quotes}
			)?									# title is optional
			\)
			@x',
			array($this, 'replaceLink'),
			$text
		);
		$replaced = preg_replace_callback(
			'@
			\[(?<anchor>.+?)\]\ ?\[(?<id>.*?)\]
			@x',
			array($this, 'replaceLinkDefinition'),
			$replaced
		);

		return $replaced;
	}

	private function replaceLink($regexMatch)
	{
		$url = ($regexMatch['url1']) ?: $regexMatch['url2'];
		$title = isset($regexMatch['title']) ? $regexMatch['title'] : null;
		return $this->createLink($title, $url, $regexMatch['anchor']);
	}

	private function replaceLinkDefinition($regexMatch)
	{
		if ($regexMatch['id'] === '')
		{
			$regexMatch['id'] = $regexMatch['anchor'];
		}

		$linkDef = $this->linkDefinitions->get($regexMatch['id']);

		if (!$linkDef)
		{
			throw new \Exception('Following link definition not found: "['
				. $regexMatch['id'] . ']"'
			);
		}

		$title = $linkDef->getTitle();
		$url = $linkDef->getUrl();
		$anchorText = $regexMatch['anchor'];

		return $this->createLink($title, $url, $anchorText);
	}

	private function createLink($title, $url, $anchorText)
	{
		if ($title)
		{
			$titleAttr = " title=\"$title\"";
		}
		else
		{
			$titleAttr = "";
		}

		if ($this->isRelative($url))
		{
			$url = $this->relativeUrlBuilder->buildUrl($url);
		}
		$url = str_replace('&', '&amp;', $url);
		$url = str_replace('<', '&lt;', $url);
		$url = str_replace('"', '&quot;', $url);

		return '{{a href="' . $url . '"' . $titleAttr . '}}' . $anchorText . '{{/a}}';
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