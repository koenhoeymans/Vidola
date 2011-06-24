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

	private $doneCombinations = array();

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
			$patternList->addRootPattern($this->getPattern($pattern));
			$this->addSubpatterns($pattern, $patternList, $config);
		}

		$this->doneCombinations = array();

		return $patternList;
	}

	private function addSubpatterns($pattern, PatternList $patternList, Config $config)
	{
		foreach ((array) $config->get($pattern) as $subpattern)
		{
			if (in_array(array($pattern, $subpattern), $this->doneCombinations))
			{
				continue;
			}

			$this->doneCombinations[] = array($pattern, $subpattern);

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