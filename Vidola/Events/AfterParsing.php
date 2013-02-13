<?php

/**
 * @package Vidola
 */
namespace Vidola\Events;

use Vidola\Plugin\Event;
use AnyMark\ComponentTree\ComponentTree;

/**
 * @package Vidola
 */
class AfterParsing implements Event
{
	private $parsedText;

	public function __construct(ComponentTree $parsedText)
	{
		$this->parsedText = $parsedText;
	}

	public function getParsedText()
	{
		return $this->parsedText;
	}
}