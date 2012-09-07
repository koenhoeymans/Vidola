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
		$this->toc = $this->getMockBuilder(
			'\\Vidola\\Pattern\\Patterns\\TableOfContents'
		)->disableOriginalConstructor()->getMock();
		$this->nameCreator = new \Vidola\Util\HeaderBasedNameCreator(
			$this->headerFinder, $this->toc
		);
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

		$this->assertEquals('header', $this->nameCreator->getTitle('text', 'file'));
	}

	/**
	 * @test
	 */
	public function ifTitleSpecifiedInTocThatIsUsedFirst()
	{
		$this->toc
			->expects($this->atLeastOnce())
			->method('getSpecifiedTitleForPage')
			->with('page')
			->will($this->returnValue('foo'));
		
		$this->assertEquals('foo', $this->nameCreator->getTitle('text', 'page'));
	}

	/**
	 * @test
	 */
	public function fallsBackToFilenameSplitCamelcaseIfAllElseFails()
	{
		$this->toc
			->expects($this->atLeastOnce())
			->method('getSpecifiedTitleForPage')
			->with('aboutPage')
			->will($this->returnValue(null));
		$this->headerFinder
			->expects($this->atLeastOnce())
			->method('getHeadersSequentially')
			->with('text')
			->will($this->returnValue(array()));

		$this->assertEquals('About Page', $this->nameCreator->getTitle('text', 'aboutPage'));
	}
}