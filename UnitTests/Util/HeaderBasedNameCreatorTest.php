<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_HeaderBasedNameCreatorTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->headerFinder = $this->getMockBuilder(
			'\\Vidola\\Pattern\\Patterns\\TableOfContents\\HeaderFinder'
		)->disableOriginalConstructor()->getMock();
		$this->nameCreator = new \Vidola\Util\HeaderBasedNameCreator($this->headerFinder);
	}

	/**
	 * @test
	 */
	public function createsNameBasedOnFirstHeader()
	{
		$this->headerFinder
			->expects($this->atLeastOnce())
			->method('getHeadersSequentially')
			->with('text')
			->will($this->returnValue(array(array('title'=>'header'))));

		$this->assertEquals('header', $this->nameCreator->getTitle('text'));
	}
}