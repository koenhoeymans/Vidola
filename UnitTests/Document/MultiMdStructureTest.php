<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_MultiMdStructureTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->subfileDetector = $this->getMock('\\Vidola\\Util\\SubfileDetector');
		$this->toc = $this->getMockBuilder('\\Vidola\\Pattern\\Patterns\\TableOfContents')
			->disableOriginalConstructor()
			->getMock();
		$this->internalUrlBuilder = $this->getMockBuilder('\\Vidola\\Util\\InternalUrlBuilder')
			->disableOriginalConstructor()
			->getMock();
		$this->structure = new \Vidola\Document\MultiMdStructure(
			$this->subfileDetector, $this->toc, $this->internalUrlBuilder
		);
	}

	/**
	 * @test
	 */
	public function retrievesAllSubfilesOfGivenFile()
	{
		$this->subfileDetector
			->expects($this->any())
			->method('getSubfiles');

		$this->structure->getSubfiles('file');
	}

	/**
	 * @test
	 */
	public function createsTableOfContents()
	{
		$domDoc = new \DomDocument();
		$domDoc->loadXML('<doc><h1 id="id">header</h1></doc>');

		$this->toc
			->expects($this->any())
			->method('buildToc')
			->with(array(array('id'=>'id', 'level'=>'1', 'title'=>'header')), null, $domDoc);

		$this->structure->createTocNode($domDoc);
	}

	/**
	 * @test
	 */
	public function createsLinkBetweenPages()
	{
		$this->internalUrlBuilder
			->expects($this->any())
			->method('createLink');

		$this->structure->createLink('index');
	}
}