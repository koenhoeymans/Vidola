<?php

namespace Vidola\UnitTests\Support;

use \Vidola\Pattern\Pattern;

class MockPattern extends \Vidola\Pattern\Pattern
{
	private $regex;

	private $elementName;

	private $textInElement;

	public function __construct($regex, $elementName, $textInElement)
	{
		$this->regex = $regex;
		$this->elementName = $elementName;
		$this->textInElement = $textInElement;
	}

	public function getRegex()
	{
		return $this->regex;
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		return $ownerDocument->createElement($this->elementName, $this->textInElement);
	}
}