<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
abstract class Code extends Pattern
{
	protected function createCodeReplacement($code, $pre = true, \DOMNode $parentNode)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		$codeDom = $ownerDocument->createElement('code');
		$codeDom->appendChild($ownerDocument->createTextNode($code));

		if ($pre)
		{
			$preDom = $ownerDocument->createElement('pre');
			$preDom->appendChild($codeDom);

			return $preDom;
		}
		else
		{
			return $codeDom;
		}
	}
}