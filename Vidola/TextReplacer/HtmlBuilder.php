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

	public function replace($text)
	{
		foreach( $this->patternList->getPatterns() as $pattern)
		{
			// Replace: see class below
			$text = RecursivePatternReplacer::using($pattern, $this->patternList)->text($text);			
		}

		return $text;
	}
}