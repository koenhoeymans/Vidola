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
class Image implements Pattern
{
	private $linkDefinitions;

	public function __construct(LinkDefinitionCollector $linkDefinitionCollector)
	{
		$this->linkDefinitions = $linkDefinitionCollector;
	}

	public function replace($text)
	{
		return $this->replaceReference($this->replaceInline($text));
	}

	private function replaceInline($text)
	{
		return preg_replace_callback(
			'@
			(?<begin>^|\ )
			!\[(?<alt>.+)\]					# ![alternate text]
			\(								# (
			(?<path>[^\s]+)					# path
			(\ ("|\')(?<title>.+)("|\'))?	# "optional title"
			\)								# )
			(?<end>\ |$)
			@xU',
		function($match)
		{
			$title = $match['title'];
			if ($title !== '')
			{
				$title = 'title="' . $match['title'] . '" ';
			}

			return
			$match['begin']
			. '{{img '
			. 'alt="' . $match['alt'] . '" '
			. $title
			. 'src="' . $match['path'] . '"'
			. '}}'
			. $match['end'];
		},
		$text
		);
	}

	/**
	 * @todo replace circular handling
	 */
	private function replaceReference($text)
	{
		$linkDefinitions = $this->linkDefinitions;

		return preg_replace_callback(
			'@
			(?<begin>^|\ )
			!\[(?<alt>.+)\]					# ![alternate text]
			\[(?<id>.+)\]					# [id]
			(?<end>\ |$)
			@xU',
		function($match) use ($linkDefinitions)
		{
			$linkDefinition = $linkDefinitions->get($match['id']);

			if (!$linkDefinition)
			{
				throw new \Exception('Following link definition not found: "['
				. $regexMatch['id'] . ']"'
				);
			}

			$title = $linkDefinition->getTitle();
			if ($title)
			{
				$title = 'title="' . $title . '" ';
			}
			else
			{
				$title = '';
			}
				
			return
			$match['begin']
			. '{{img '
			. 'alt="' . $match['alt'] . '" '
			. $title
			. 'src="' . $linkDefinition->getUrl() . '"'
			. '}}'
			. $match['end'];
		},
		$text
		);
	}
}