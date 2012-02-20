<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Strong extends Pattern
{
	public function getRegex()
	{
		return
			'@
			(?<=\s|^)

			(?<marker>[_*])
			\g{marker}(?=\S)
			(?<content>
				(
					(?!\g{marker}).
				|
					\g{marker}(?=\S)
					.+
					\g{marker}(?<=\S)(?![a-zA-Z0-9])
				|
					\s\g{marker}\s						# five * three or 5 * 3
				|
					[a-zA-Z0-9]\g{marker}[a-zA-Z0-9]	# five*three or 5*3
				|
					(?!\g{marker}).*\g{marker}(?!\g{marker}).*
				)+
			)
			(?<=\S)
			\g{marker}
			\g{marker}

			(?![a-zA-Z0-9])
			@xU';
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		$strong = $ownerDocument->createElement('strong');
		$strong->appendChild($ownerDocument->createTextNode($match['content']));

		return $strong;
	}
}