<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_PatternListTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->patternList = new \Vidola\Pattern\PatternList();
	}

	/**
	 * @test
	 */
	public function keepsListOfAllSpecifiedPatterns()
	{
		// given
		$patternA = new \Vidola\UnitTests\Support\MockPattern('@x@', 'a', 'b');
		$patternB = new \Vidola\UnitTests\Support\MockPattern('@x@', 'a', 'b');

		// when
		$this->patternList->addRootPattern($patternA);
		$this->patternList->addRootPattern($patternB);

		// then
		$this->assertEquals(
			array($patternA, $patternB),
			$this->patternList->getPatterns()
		);
	}

	/**
	 * @test
	 */
	public function keepsListOfSpecifiedSubpatterns()
	{
		// given
		$patternA = new \Vidola\UnitTests\Support\MockPattern('@x@', 'a', 'b');
		$patternB = new \Vidola\UnitTests\Support\MockPattern('@x@', 'a', 'b');
		$patternC = new \Vidola\UnitTests\Support\MockPattern('@x@', 'a', 'b');

		// when
		$this->patternList->addRootPattern($patternA);
		$this->patternList->addSubpattern($patternB, $patternA);
		$this->patternList->addSubpattern($patternC, $patternA);

		// then
		$this->assertEquals(
			array($patternB, $patternC),
			$this->patternList->getSubpatterns($patternA)
		);
	}
}