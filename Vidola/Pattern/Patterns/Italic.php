<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Italic extends Pattern
{
	public function getRegex()
	{
		return
			"@
				(?<=\s|^)
			_
				(?=\S)
			(
				(
					(?!_).
				|
					_(?=\S)
					.*[^_].*
					_(?<=\S)(?!\w)
				|
					(?!_).*_(?!_).*
				)+
			)
				(?<!\s)
			_
				(?!\w)
			@xU";
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		$i = $ownerDocument->createElement('i');
		$i->appendChild($ownerDocument->createTextNode($match[1]));

		return $i;
	}
}