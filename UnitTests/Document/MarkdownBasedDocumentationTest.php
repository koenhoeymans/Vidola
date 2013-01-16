<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_MarkdownBasedDocumentationTest extends PHPUnit_Framework_TestCase
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
		$this->tocGenerator = $this->getMock('\\Vidola\\Util\\TocGenerator');
		$this->internalUrlBuilder = $this->getMock('\\AnyMark\\Util\\InternalUrlBuilder');

		$this->mdDoc = new \Vidola\Document\MarkdownBasedDocumentation(
			$this->anyMark,
			$this->titleCreator,
			$this->tocGenerator,
			$this->internalUrlBuilder
		);
	}

	/**
	 * @test
	 */
	public function buildsApiBasedOnPage()
	{
		$this->assertTrue(
			$this->mdDoc->buildApi(new \Vidola\Document\Page('page', ''))
			instanceof
			\Vidola\Document\MarkdownBasedDocumentationViewApi
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
			$this->mdDoc->getParsedContent($page)
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
			$this->mdDoc->getParsedContent($page, true)
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
			$this->mdDoc->getTitle(new \Vidola\Document\Page('a_page', 'content'))
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

		$this->tocGenerator
			->expects($this->atLeastOnce())
			->method('createTocNode')
			->with($domDoc, 1);

		$this->mdDoc->getToc(new \Vidola\Document\Page('a_page', 'content'), 1);
	}

	/**
	 * @test
	 */
	public function keepsListOfAllPages()
	{
		$page1 = new \Vidola\Document\Page('page1', 'content');
		$page2 = new \Vidola\Document\Page('page2', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->assertEquals(array($page1, $page2), $this->mdDoc->getPages());
	}

	/**
	 * @test
	 */
	public function getPreviousPage()
	{
		$page1 = new \Vidola\Document\Page('page1', 'content');
		$page2 = new \Vidola\Document\Page('page2', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->assertEquals($page1, $this->mdDoc->getPreviousPage($page2));
	}

	/**
	 * @test
	 */
	public function getNextPage()
	{
		$page1 = new \Vidola\Document\Page('page1', 'content');
		$page2 = new \Vidola\Document\Page('page2', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->assertEquals($page2, $this->mdDoc->getNextPage($page1));
	}

	/**
	 * @test
	 */
	public function startPageIsFirstPageAdded()
	{
		$page1 = new \Vidola\Document\Page('page1', 'content');
		$page2 = new \Vidola\Document\Page('page2', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->assertEquals($page1, $this->mdDoc->getStartPage());
	}

	/**
	 * @test
	 */
	public function createsUrlFromPageToAnother()
	{
		$page1 = new \Vidola\Document\Page('index', 'content');
		$page2 = new \Vidola\Document\Page('../subpage', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->internalUrlBuilder
			->expects($this->atLeastOnce())
			->method('createRelativeLink')
			->with()
			->will($this->returnValue('../index'));

		$this->assertEquals('../index', $this->mdDoc->getUrl($page2, $page1));
	}

	/**
	 * @test
	 */
	public function providesBreadCrumbsAsListOfPages()
	{
		$page1 = new \Vidola\Document\Page('index', 'content');
		$page2 = new \Vidola\Document\Page('../subpage', 'content');
		$page3 = new \Vidola\Document\Page('../sub/subpage', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2, $page1);
		$this->mdDoc->add($page3, $page2);

		$this->assertEquals(
			array($page1, $page2, $page3),
			$this->mdDoc->getBreadCrumbs($page3)
		);
	}
}