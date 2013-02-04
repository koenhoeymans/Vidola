<?php

/**
 * @package Vidola
 */
namespace Vidola\Events;

use Vidola\Plugin\Event;

/**
 * @package Vidola
 */
class AfterParsing implements Event
{
	private $parsedText;

	public function __construct(\DomDocument $parsedText)
	{
		$this->parsedText = $parsedText;
	}

	public function getParsedText()
	{
		return $this->parsedText;
	}
}