<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_PatternListFillerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->filler = new \Vidola\Util\PatternListFiller(
			new \Vidola\Util\ObjectGraphConstructor
		);
	}

	/**
	 * @test
	 */
	public function canFillPatternListFromIni()
	{
		$patternListMock = $this->getMock('Vidola\\Pattern\\PatternList');
		$patternListMock
			->expects($this->once())
			->method('addRootPattern')
			->with(new \Vidola\Pattern\Patterns\Bold());
		$patternListMock
			->expects($this->once())
			->method('addSubPattern')
			->with(new \Vidola\Pattern\Patterns\Italic(), new \Vidola\Pattern\Patterns\Bold());
		$dummyIni = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'Dummy.ini';

		$this->filler->fill($patternListMock, $dummyIni);
	}

	/**
	 * @test
	 */
	public function handlesCircularDependencies()
	{
		$patternList = new \Vidola\Pattern\PatternList();
		$circularIni = __DIR__
				. DIRECTORY_SEPARATOR . '..'
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'Circular.ini';

		// shouldn't throw maximum nesting level error
		$this->filler->fill($patternList, $circularIni);
	}
}