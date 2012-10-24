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
		$this->contentRetriever = $this->getMockBuilder('\\Vidola\\Util\\ContentRetriever')
			->disableOriginalConstructor()
			->getMock();
		$this->parser = $this->getMockBuilder('\\Vidola\\Parser\\Parser')
			->disableOriginalConstructor()
			->getMock();
		$this->titleCreator = $this->getMockBuilder('\\Vidola\\Util\\TitleCreator')
			->disableOriginalConstructor()
			->getMock();
		$this->internalUrlBuilder = $this->getMockBuilder('\\Vidola\\Util\\InternalUrlBuilder')
			->disableOriginalConstructor()
			->getMock();
		$this->toc = $this->getMockBuilder('\\Vidola\\Pattern\\Patterns\\TableOfContents')
			->disableOriginalConstructor()
			->getMock();
		$this->mdDoc = new \Vidola\Document\MarkdownBasedDocumentation(
			'my_project', $this->contentRetriever, $this->parser, $this->titleCreator,
			$this->internalUrlBuilder, $this->toc
		);
	}

	/**
	 * @test
	 */
	public function buildsApiBasedOnPage()
	{
		$this->assertTrue(
			$this->mdDoc->buildApi('my_page')
			instanceof
			\Vidola\Document\MarkdownBasedDocumentationViewApi
		);
	}

	/**
	 * @test
	 */
	public function providesParsedContentOfFileAsString()
	{
		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('about')
			->will($this->returnValue('text'));
		$this->parser
			->expects($this->atLeastOnce())
			->method('parse')
			->with('text')
			->will($this->returnValue($this->createDom('<doc>parsed text</doc>')));

		$this->assertEquals('<doc>parsed text</doc>', $this->mdDoc->getParsedContent('about'));
	}

	/**
	 * @test
	 */
	public function providesParsedContentAsDomDocument()
	{
		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('foo')
			->will($this->returnValue('text'));
		$this->parser
			->expects($this->atLeastOnce())
			->method('parse')
			->with('text')
			->will($this->returnValue($this->createDom('<doc>parsed text</doc>')));

		$this->assertEquals(
			$this->createDom('<doc>parsed text</doc>'),
			$this->mdDoc->getParsedContent('foo', true)
		);
	}

	/**
	 * @test
	 */
	public function cachesParsedContent()
	{
		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('x')
			->will($this->returnValue('text'));
		$this->parser
			->expects($this->once())
			->method('parse')
			->with('text')
			->will($this->returnValue($this->createDom('<doc>parsed text</doc>')));
	
		$this->mdDoc->getParsedContent('x');
		$this->mdDoc->getParsedContent('x');
	}

	/**
	 * @test
	 */
	public function afterProcessingPostTextProcessorsAreCalled()
	{
		$postProcessor = $this->getMock('\\Vidola\\Processor\\TextProcessor');
		$postProcessor
			->expects($this->atLeastOnce())
			->method('process');
		$this->mdDoc->addPostTextProcessor($postProcessor);

		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('a_page')
			->will($this->returnValue('text'));
		$this->parser
			->expects($this->atLeastOnce())
			->method('parse')
			->with('text')
			->will($this->returnValue($this->createDom('<doc>parsed text</doc>')));

		$this->mdDoc->getParsedContent('a_page');
	}

	/**
	 * @test
	 */
	public function createsPageTitle()
	{
		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('index')
			->will($this->returnValue('text'));
		$this->titleCreator
			->expects($this->atLeastOnce())
			->method('createPageTitle')
			->with('text')
			->will($this->returnValue('title'));

		$this->assertEquals('title', $this->mdDoc->getPageTitle('index'));
	}

	/**
	 * @test
	 */
	public function createsLinkBetweenPages()
	{
		$this->internalUrlBuilder
			->expects($this->any())
			->method('createRelativeLink');
	
		$this->mdDoc->getLink('index');
	}

	/**
	 * @test
	 */
	public function retrievesAllSubpagesOfGivenPage()
	{
		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('foo')
			->will($this->returnValue('text'));
		$this->toc
			->expects($this->any())
			->method('getSubpages')
			->with('text')
			->will($this->returnValue(array('subpage')));

		$this->assertEquals(array('subpage'), $this->mdDoc->getSubpages('foo'));
	}

	/**
	 * @test
	 */
	public function getPreviousPage()
	{
		$this->contentRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('my_project')
			->will($this->returnValue('text'));
		$this->contentRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('second_page')
			->will($this->returnValue('second_page_text'));
		$this->contentRetriever
			->expects($this->at(2))
			->method('retrieve')
			->with('third_page')
			->will($this->returnValue('third_page_text'));
		$this->toc
			->expects($this->at(0))
			->method('getSubpages')
			->with('text')
			->will($this->returnValue(array('second_page', 'third_page')));
		$this->toc
			->expects($this->at(1))
			->method('getSubpages')
			->with('second_page_text')
			->will($this->returnValue(array()));
		$this->toc
			->expects($this->at(2))
			->method('getSubpages')
			->with('third_page_text')
			->will($this->returnValue(array()));

		$this->assertEquals('second_page', $this->mdDoc->getPreviousPage('third_page'));
	}

	/**
	 * @test
	 */
	public function getNextPage()
	{
		$this->contentRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('my_project')
			->will($this->returnValue('text'));
		$this->contentRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('second_page')
			->will($this->returnValue('second_page_text'));
		$this->contentRetriever
			->expects($this->at(2))
			->method('retrieve')
			->with('third_page')
			->will($this->returnValue('third_page_text'));
		$this->toc
			->expects($this->at(0))
			->method('getSubpages')
			->with('text')
			->will($this->returnValue(array('second_page', 'third_page')));
		$this->toc
			->expects($this->at(1))
			->method('getSubpages')
			->with('second_page_text')
			->will($this->returnValue(array()));
		$this->toc
			->expects($this->at(2))
			->method('getSubpages')
			->with('third_page_text')
			->will($this->returnValue(array()));

		$this->assertEquals('third_page', $this->mdDoc->getNextPage('second_page'));
	}

	/**
	 * @test
	 */
	public function createsTableOfContents()
	{
		$domDoc = $this->createDom('<doc><h1 id="id">header</h1>parsed text</doc>');

		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('page')
			->will($this->returnValue('text'));
		$this->parser
			->expects($this->atLeastOnce())
			->method('parse')
			->with('text')
			->will($this->returnValue($domDoc));

		$this->toc
			->expects($this->any())
			->method('buildToc')
			->with(array(array('id'=>'id', 'level'=>'1', 'title'=>'header')), null, $domDoc);

		$this->mdDoc->getToc('page');
	}

	/**
	 * @test
	 */
	public function providesBreadCrumbsAsListOfFiles()
	{
		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('my_project')
			->will($this->returnValue('text'));
		$this->toc
			->expects($this->any())
			->method('getSubpages')
			->with('text')
			->will($this->returnValue(array('subpage')));

		$this->assertEquals(
			array('my_project', 'subpage'),
			$this->mdDoc->getBreadCrumbs('subpage')
		);
	}
}