<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_MarkdownBasedDocumentationTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->contentRetriever = $this->getMock('\\Vidola\\Util\\ContentRetriever');
		$this->parser = $this->getMock('\\Vidola\\Parser\\Parser');
		$this->subfileDetector = $this->getMock('\\Vidola\\Util\\SubfileDetector');
		$this->internalUrlBuilder = $this->getMock('\\Vidola\\Util\\InternalUrlBuilder');
		$this->toc = $this->getMockBuilder('\\Vidola\\Pattern\\Patterns\\TableOfContents')
			->disableOriginalConstructor()
			->getMock();
		$this->titleCreator = $this->getMockBuilder('\\Vidola\\Util\\TitleCreator')
			->getMock();
		$this->mdDoc = new \Vidola\Document\MarkdownBasedDocumentation(
			'file',
			$this->contentRetriever,
			$this->parser,
			$this->subfileDetector,
			$this->internalUrlBuilder,
			$this->toc,
			$this->titleCreator
		);
	}

	/**
	 * @test
	 */
	public function buildsApiBasedOnFile()
	{
		$this->assertTrue(
			$this->mdDoc->buildApi('file')
			instanceof
			\Vidola\Document\MarkdownBasedDocumentViewApi
		);
	}

	/**
	 * @test
	 */
	public function providesParsedContentOfFile()
	{
		$this->parser
			->expects($this->once())
			->method('parse')
			->will($this->returnValue('bar'));
		
		$this->mdDoc->getContent('file');
	}

	/**
	 * @test
	 */
	public function knowsSubfilesOfGivenFile()
	{
		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('file')
			->will($this->returnValue('text'));
		$this->subfileDetector
			->expects($this->atLeastOnce())
			->method('getSubfiles')
			->with('text')
			->will($this->returnValue(array('subfile')));

		$this->assertEquals(array('subfile'), $this->mdDoc->getSubfiles('file'));
	}

	/**
	 * @test
	 */
	public function asksNameCreatorForFileTitle()
	{
		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('file')
			->will($this->returnValue('text'));
		$this->titleCreator
			->expects($this->atLeastOnce())
			->method('getTitle')
			->with('text')
			->will($this->returnValue('title'));

		$this->assertEquals('title', $this->mdDoc->getFileTitle('file'));
	}

	/**
	 * @test
	 */
	public function providesListOfFiles()
	{
		$this->contentRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('file')
			->will($this->returnValue('text'));
		$this->contentRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('subfile')
			->will($this->returnValue('subtext'));
		$this->subfileDetector
			->expects($this->at(0))
			->method('getSubfiles')
			->with('text')
			->will($this->returnValue(array('subfile')));
		$this->subfileDetector
			->expects($this->at(1))
			->method('getSubfiles')
			->with('subtext')
			->will($this->returnValue(array()));

		$this->assertEquals(
			array('file', 'subfile'),
			$this->mdDoc->getFileList()
		);
	}

	/**
	 * @test
	 */
	public function hasTocOfFile()
	{
		$this->contentRetriever
			->expects($this->atLeastOnce())
			->method('retrieve')
			->with('file')
			->will($this->returnValue('text with header in'));
		$this->toc
			->expects($this->atLeastOnce())
			->method('createTocNode')
			->with('text with header in', new \DomDocument())
			->will($this->returnValue('this should be a domNode'));

		$this->assertEquals(
			'this should be a domNode',
			$this->mdDoc->getToc('file')
		);
	}

	/**
	 * @test
	 */
	public function providesBreadCrumbsAsListOfFiles()
	{
		$this->contentRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('file')
			->will($this->returnValue('some text'));
		$this->subfileDetector
			->expects($this->at(0))
			->method('getSubfiles')
			->with('some text')
			->will($this->returnValue(array('subfile')));

		$this->assertEquals(
			array('file', 'subfile'),
			$this->mdDoc->getBreadCrumbs('subfile')
		);
	}

	/**
	 * @test
	 */
	public function givesFileNameTakenSubfolderIntoAccount()
	{
		$this->assertEquals('subfolder/index', $this->mdDoc->createFileName('subfolder/index.txt'));
	}

	/**
	 * @test
	 */
	public function pointsToHigherDirectoryIfCurrentFileIsInSubdirectoryVersusPrevious()
	{
		$this->contentRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('file')
			->will($this->returnValue('some text'));
		$this->contentRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('subfolder/subdocument')
			->will($this->returnValue('subtext'));
		$this->subfileDetector
			->expects($this->at(0))
			->method('getSubfiles')
			->with('some text')
			->will($this->returnValue(array('subfolder/subdocument')));
		$this->subfileDetector
			->expects($this->at(1))
			->method('getSubfiles')
			->with('subtext')
			->will($this->returnValue(array()));
		$this->internalUrlBuilder
			->expects($this->once())
			->method('createLInk')
			->with('file', 'subfolder/subdocument')
			->will($this->returnValue('file'));

		$this->assertEquals(
			'file', $this->mdDoc->getPreviousFileLink('subfolder/subdocument')
		);
	}

	/**
	 * @test
	 */
	public function pointsToHigherDirectoryIfCurrentFileIsInSubdirectoryVersusNext()
	{
		$this->contentRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('file')
			->will($this->returnValue('some text'));
		$this->contentRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('subfolder/subdocument')
			->will($this->returnValue('subtext'));
		$this->contentRetriever
			->expects($this->at(2))
			->method('retrieve')
			->with('nextfile')
			->will($this->returnValue('nexttext'));
		$this->subfileDetector
			->expects($this->at(0))
			->method('getSubfiles')
			->with('some text')
			->will($this->returnValue(array('subfolder/subdocument')));
		$this->subfileDetector
			->expects($this->at(1))
			->method('getSubfiles')
			->with('subtext')
			->will($this->returnValue(array('nextfile')));
			$this->subfileDetector
			->expects($this->at(2))
			->method('getSubfiles')
			->with('nexttext')
			->will($this->returnValue(array()));
		$this->internalUrlBuilder
			->expects($this->once())
			->method('createLInk')
			->with('nextfile', 'subfolder/subdocument')
			->will($this->returnValue('file'));

		$this->assertEquals(
			'file', $this->mdDoc->getNextFileLink('subfolder/subdocument')
		);
	}

	/**
	 * @test
	 */
	public function createsStartFileLinkRelativeToGivenFile()
	{
		$this->internalUrlBuilder
			->expects($this->once())
			->method('createLInk')
			->with('file', 'subfile')
			->will($this->returnValue('file'));

		$this->assertEquals(
			'file', $this->mdDoc->getStartFileLink('subfile')
		);
	}
}