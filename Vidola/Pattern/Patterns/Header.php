<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;
use Vidola\Document\Element;

/**
 * @package Vidola
 */
class Header extends Pattern
{
	private $headerList = array(
		1 => array('before' => null, 'after' => null),
		2 => array('before' => null, 'after' => null),
		3 => array('before' => null, 'after' => null),
		4 => array('before' => null, 'after' => null),
		5 => array('before' => null, 'after' => null),
		6 => array('before' => null, 'after' => null)
	);

	public function getRegex()
	{
		return
		'@
		(?<=^|\n)
		(?<setext>
			([ ]{0,3}(?<pre>[-=+*^#]{3,})\n)?
			[ ]{0,3}(?<text>\S.*)\n
			[ ]{0,3}(?<post>[-=+*^#]{3,})
		)
		(?=\n|$)

		|

		(?<=^|\n\n)
		(?<atx>(?J)
			[ ]{0,3}(?<level>[#]{1,6})[ ]?(?<text>[^\n]+?)([ ]?[#]*)
		)
		(?=\n|$)
		@x';
	}

	public function handleMatch(array $match, \DOMNode $parentNode, Pattern $parentPattern = null)
	{
		$ownerDocument = $this->getOwnerDocument($parentNode);
		if (isset($match['atx']))
		{
			return $this->createAtxHeaders($match, $ownerDocument);
		}
		else
		{
			return $this->createSetextHeaders($match, $ownerDocument);
		}
	}

	private function createSetextHeaders(array $match, \DOMDocument $domDoc)
	{
		foreach ($this->headerList as $level => $header)
		{
			if ($header['after'] === null)
			{
				$this->headerList[$level]['before'] = substr($match['pre'], 0, 3);
				$this->headerList[$level]['after'] = substr($match['post'], 0, 3);
				break;
			}
			if ($header['before'] === substr($match['pre'], 0, 3)
				&& $header['after'] === substr($match['post'], 0, 3))
			{
				break;
			}
		}

		$h = $domDoc->createElement('h' . $level);
		$h->appendChild($domDoc->createTextNode($match['text']));

		return $h;
	}

	private function createAtxHeaders(array $match, \DOMDocument $domDoc)
	{
		
		$level = strlen($match['level']);
		$level = ($level > 5) ? 6 : $level;

		$h = $domDoc->createElement('h' . $level);
		$h->appendChild($domDoc->createTextNode($match['text']));
		
		return $h;
	}
}