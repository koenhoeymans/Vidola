<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_PatternCreatorTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->patternCreator = new \Vidola\Util\PatternCreator();
	}

	/**
	 * @test
	 */
	public function createsPatternBasedOnPatternNameInPatternFolder()
	{
		$this->assertEquals(
			new \Vidola\Patterns\Bold(),
			$this->patternCreator->get('bold')
		);
	}

	/**
	 * @test
	 */
	public function createsConstructorDependencies()
	{
		$this->assertEquals(
			new \Vidola\Patterns\Hyperlink(new \Vidola\Patterns\LinkDefinitionCollector()),
			$this->patternCreator->get('hyperlink')
		);
	}
}