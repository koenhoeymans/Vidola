<?php

/**
 * @package Vidola
 */
namespace Vidola\TextReplacer;

use \Vidola\Patterns\Pattern;
use \Vidola\Patterns\PatternList;

/**
 * @package vidola
 */
class HtmlBuilder implements TextReplacer
{
	private $patternList;

	public function __construct(PatternList $patternList)
	{
		$this->patternList = $patternList;
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::getExtension()
	 */
	public function getExtension()
	{
		return 'html';
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::replace()
	 */
	public function replace($text)
	{
		foreach( $this->patternList->getPatterns() as $pattern)
		{
			$text = RecursivePatternReplacer::replaceRecursively(
				$text, $pattern, $this->patternList
			);
		}

		return $text;
	}
}