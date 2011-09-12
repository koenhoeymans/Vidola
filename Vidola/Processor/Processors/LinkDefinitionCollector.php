<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\Processor;

/**
 * @package Vidola
 */
class LinkDefinitionCollector implements Processor
{
	private $linkDefinitions = array();

	public function process($text)
	{
		return preg_replace_callback(
			"#(\n\s*|^\s*)\[(.+)\]: (.+?)( \"(.+)\")?(?=\n|$)#",
			array($this, 'save'),
			$text
		);
	}

	private function save($linkDefinition)
	{
		$name = $linkDefinition[2];
		$url = $linkDefinition[3];
		$title = isset($linkDefinition[5]) ? $linkDefinition[5] : null;
		$this->linkDefinitions[$name] = new \Vidola\Pattern\Patterns\LinkDefinition($name, $url, $title);
	}

	/**
	 * Returns a link definition based on reference.
	 * 
	 * @param string $linkDefinition
	 * @return Vidola\Pattern\Patterns\LinkDefinition
	 */
	public function get($linkDefinition)
	{
		if (!isset($this->linkDefinitions[$linkDefinition]))
		{
			return null;
		}

		return $this->linkDefinitions[$linkDefinition];
	}
}