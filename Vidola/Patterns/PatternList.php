<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

use Vidola\Util\config;

/**
 * @package vidola
 * 
 * List of patterns with its subpatterns.
 */
class PatternList
{
	private $patterns = array();

	private $subpatterns = array();

	/**
	 * @return array
	 */
	public function getRootPatterns()
	{
		return $this->patterns;
	}

	/**
	 * @param Pattern $pattern
	 * @return array
	 */
	public function getSubpatterns(Pattern $pattern)
	{
		foreach ($this->subpatterns as $patternArr)
		{
			if ($patternArr['pattern'] == $pattern)
			{
				return $patternArr['subpatterns'];
			}
		}

		return array();
	}

	/**
	 * @param Pattern $pattern
	 * 
	 * @return PatternList
	 */
	public function addRootPattern(Pattern $pattern)
	{
		$this->patterns[] = $pattern;

		return $this;
	}

	/**
	 * @param Pattern $subpattern
	 * @param Pattern $pattern
	 * 
	 * @return PatternList
	 */
	public function addSubpattern(Pattern $subpattern, Pattern $pattern)
	{
		foreach ($this->subpatterns as &$patternArr)
		{
			if ($patternArr['pattern'] == $pattern)
			{
				$patternArr['subpatterns'][] = $subpattern;
				return $this;
			}
		}

		$this->subpatterns[] = array(
			'pattern' => $pattern, 'subpatterns' => array($subpattern)
		);

		return $this;
	}
}