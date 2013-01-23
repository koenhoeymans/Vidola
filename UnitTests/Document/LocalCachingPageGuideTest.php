<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_LocalCachingPageGuideTest extends PHPUnit_Framework_TestCase
{
	private function createDom($string)
	{
		$domDoc = new \DomDocument();
		$domDoc->loadXML($string);

		return $domDoc;
	}

	public function __construct()
	{
		$this->anyMark = $anyMark = $this->getMockBuilder('\\AnyMark\\AnyMark')
			->disableOriginalConstructor()
			->getMock();
		$this->titleCreator = $this->getMock('\\Vidola\\Util\\TitleCreator');
		$this->toc = $this->getMockBuilder('\\Vidola\\Pattern\\Patterns\\TableOfContents')
			->disableOriginalConstructor()
			->getMock();

		$this->pageGuide = new \Vidola\Document\LocalCachingPageGuide(
			$this->anyMark,
			$this->titleCreator,
			$this->toc
		);
	}

	/**
	 * @test
	 */
	public function providesParsedContentOfPageAsString()
	{
		$page = new \Vidola\Document\Page('a_page', 'content');

		$this->anyMark
			->expects($this->atLeastOnce())
			->method('parse')
			->with('content')
			->will($this->returnValue($this->createDom('<doc>parsed text</doc>')));
		$this->anyMark
			->expects($this->once())
			->method('saveXml')
			->with($this->createDom('<doc>parsed text</doc>'))
			->will($this->returnValue('<doc>parsed text</doc>'));

		$this->assertEquals(
			'<doc>parsed text</doc>',
			$this->pageGuide->getParsedContent($page)
		);
	}

	/**
	 * @test
	 */
	public function providesParsedContentAsDomDocument()
	{
		$page = new \Vidola\Document\Page('a_page', 'content');

		$this->anyMark
			->expects($this->atLeastOnce())
			->method('parse')
			->with('content')
			->will($this->returnValue($this->createDom('<doc>parsed text</doc>')));

		$this->assertEquals(
			$this->createDom('<doc>parsed text</doc>'),
			$this->pageGuide->getParsedContent($page, true)
		);
	}

	/**
	 * @test
	 */
	public function createsPageTitle()
	{
		$this->titleCreator
			->expects($this->atLeastOnce())
			->method('createPageTitle')
			->with('content', 'a_page')
			->will($this->returnValue('title'));

		$this->assertEquals(
			'title',
			$this->pageGuide->getTitle(new \Vidola\Document\Page('a_page', 'content'))
		);
	}

	/**
	 * @test
	 */
	public function createsTableOfContents()
	{
		$domDoc = $this->createDom('<doc><h1 id="id">header</h1>parsed text</doc>');

		$this->anyMark
			->expects($this->atLeastOnce())
			->method('parse')
			->with('content')
			->will($this->returnValue($domDoc));

		$this->toc
			->expects($this->atLeastOnce())
			->method('createTocNode')
			->with($domDoc, 1);

		$this->pageGuide->getToc(new \Vidola\Document\Page('a_page', 'content'), 1);
	}
}