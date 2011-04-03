<?php

namespace Vidola\EndToEndTests\Support;

class SetupCreator
{
	private $patternList;

	public function createSetup($patterns)
	{
		$args = func_get_args();
		$this->patternList = new \Vidola\Patterns\PatternList();
		$this->createPatternStructure($args);

		return new \Vidola\TextReplacer\HtmlBuilder($this->patternList);
	}

	private function createPatternStructure(array $patterns)
	{
		foreach($patterns as $patternArr)
		{
			$pattern = "\\Vidola\\Patterns\\" . ucfirst($patternArr['pattern']);
			$pattern = new $pattern();
			$this->patternList->addPattern($pattern);
			$this->addSubpatterns($pattern, $patternArr['subpatterns']);
		}
	}

	private function addSubpatterns($pattern, array $subpatterns)
	{
		foreach($subpatterns as $subpatternArr)
		{
			$subpattern = "\\Vidola\\Patterns\\" . ucfirst($subpatternArr['pattern']);
			$subpattern = new $subpattern();
			$this->patternList->addSubpattern(
				$subpattern, $pattern
			);
			$this->addSubpatterns($subpattern, $subpatternArr['subpatterns']);
		}
	}

	public function __call($function, $args = array())
	{
		return array('pattern' => $function, 'subpatterns' => $args);
	}
}