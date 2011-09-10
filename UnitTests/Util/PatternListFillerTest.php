<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_PatternListFillerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->dummyConfig = __DIR__
				. DIRECTORY_SEPARATOR . '..'
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'Dummy.ini';
		$ogc = new \Vidola\Util\ObjectGraphConstructor;
		$this->filler = new \Vidola\Util\PatternListFiller($ogc);
	}

	/**
	 * @test
	 */
	public function handlesCircularDependencies()
	{
		$patternList = new \Vidola\Pattern\PatternList();
		$config = new \Vidola\Config\CommandLineIniConfig(
			array('root' => array('Bold'), 'Bold' => array('Italic'), 'Italic' => 'Bold'),
			$this->dummyConfig
		);

		// shouldn't throw maximum nesting level error
		$this->filler->fill($patternList, $config);
	}
}