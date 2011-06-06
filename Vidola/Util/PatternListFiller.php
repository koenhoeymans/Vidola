<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use Vidola\Patterns\PatternList;
use Vidola\Util\ObjectGraphConstructor;
use Vidola\Util\Config;

/**
 * @package Vidola
 */
class PatternListFiller
{
	private $objectGraphConstructor;

	public function __construct(ObjectGraphConstructor $objectGraphConstructor)
	{
		$this->objectGraphConstructor = $objectGraphConstructor;
	}

	/**
	 * Fill PatternList based on patterns in config.
	 * 
	 * @param PatternList $patternList
	 * @param Config $config
	 * @return PatternList
	 */
	public function fill(PatternList $patternList, Config $config)
	{
		foreach ($config->get('root') as $pattern)
		{
			$patternList->addPattern($this->getPattern($pattern));
			$this->addSubpatterns($pattern, $patternList, $config);
		}

		return $patternList;
	}

	private function addSubpatterns($pattern, PatternList $patternList, Config $config)
	{
		foreach ((array) $config->get($pattern) as $subpattern)
		{
			$patternList->addSubpattern(
				$this->getPattern($subpattern),
				$this->getPattern($pattern)
			);
			if ($pattern != $subpattern)
			{
				$this->addSubpatterns($subpattern, $patternList, $config);
			}
		}
	}

	private function getPattern($name)
	{
		return $this->objectGraphConstructor->getInstance(
			'Vidola\\Patterns\\' . ucfirst($name)
		);
	}
}