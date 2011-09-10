<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use Vidola\Pattern\PatternList;
use Vidola\Util\ObjectGraphConstructor;
use Vidola\Config\Config;

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
	public function fill(PatternList $patternList, $iniFile)
	{
		$patternTree = parse_ini_file($iniFile);

		foreach ($patternTree['root'] as $pattern)
		{
			$patternList->addRootPattern($this->getPattern($pattern));
			$this->addSubpatterns($pattern, $patternList, $patternTree);
		}

		$this->doneCombinations = array();

		return $patternTree;
	}

	private function addSubpatterns($pattern, PatternList $patternList, array $patternTree)
	{
		if (!isset($patternTree[$pattern]))
		{
			return;
		}

		foreach ($patternTree[$pattern] as $subpattern)
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
				$this->addSubpatterns($subpattern, $patternList, $patternTree);
			}
		}
	}

	private function getPattern($name)
	{
		return $this->objectGraphConstructor->getInstance(
			'Vidola\\Pattern\\Patterns\\' . ucfirst($name)
		);
	}
}