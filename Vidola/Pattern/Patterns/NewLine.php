<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class NewLine extends Pattern
{
	public function getRegex()
	{
		return "@[ ][ ](?=\n)@";
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		return $this->getOwnerDocument($parentNode)->createElement('br');
	}
}