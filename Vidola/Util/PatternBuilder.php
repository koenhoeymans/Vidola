<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

use Vidola\Patterns\PatternList;
use Vidola\Util\Config;

/**
 * @package Vidola
 */
class PatternBuilder
{
	private $patterns = array();

	public function build(PatternList $patternList, Config $config)
	{
		foreach ($config->get('root') as $pattern)
		{
			$patternList->addPattern($this->getPattern($pattern));
			$this->addSubpatterns($pattern, $patternList, $config);
		}
	}

	private function addSubpatterns($pattern, PatternList $patternList, Config $config)
	{
		foreach ((array) $config->get($pattern) as $subpattern)
		{
			$patternList->addSubpattern(
				$this->getPattern($subpattern),
				$this->getPattern($pattern)
			);
			$this->addSubpatterns($subpattern, $patternList, $config);
		}
	}

	private function getPattern($name)
	{
		$class = '\\Vidola\\Patterns\\' . ucfirst($name);

		if (!isset($this->patterns[$class]))
		{
			$this->patterns[$class] = new $class;
		}

		return $this->patterns[$class];
	}
}