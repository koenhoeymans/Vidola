<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_MarkdownBasedDocumentationTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->content = $this->getMock('\\Vidola\\Document\\Content');
		$this->subfileDetector = $this->getMock('\\Vidola\\Util\\SubfileDetector');
		$this->internalUrlBuilder = $this->getMock('\\Vidola\\Util\\InternalUrlBuilder');
		$this->toc = $this->getMockBuilder('\\Vidola\\Pattern\\Patterns\\TableOfContents')
			->disableOriginalConstructor()
			->getMock();
		$this->titleCreator = $this->getMockBuilder('\\Vidola\\Util\\TitleCreator')
			->getMock();
		$this->mdDoc = new \Vidola\Document\MarkdownBasedDocumentation(
			'file',
			$this->content,
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
			\Vidola\Document\MarkdownBasedDocumentationViewApi
		);
	}

	/**
	 * @test
	 */
	public function providesParsedContentOfFile()
	{
		$this->content
			->expects($this->once())
			->method('getContent');
		
		$this->mdDoc->getContent('file');
	}

	/**
	 * @test
	 */
	public function knowsSubfilesOfGivenFile()
	{
		$this->content
			->expects($this->atLeastOnce())
			->method('getContent')
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
		$this->content
			->expects($this->atLeastOnce())
			->method('getContent')
			->with('file')
			->will($this->returnValue('text'));
		$this->titleCreator
			->expects($this->atLeastOnce())
			->method('createPageTitle')
			->with('text')
			->will($this->returnValue('title'));

		$this->assertEquals('title', $this->mdDoc->getPageTitle('file'));
	}

	/**
	 * @test
	 */
	public function providesListOfFiles()
	{
		$this->content
			->expects($this->at(0))
			->method('getContent')
			->with('file')
			->will($this->returnValue('text'));
		$this->content
			->expects($this->at(1))
			->method('getContent')
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
		$this->content
			->expects($this->atLeastOnce())
			->method('getContent')
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
		$this->content
			->expects($this->at(0))
			->method('getContent')
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
		$this->content
			->expects($this->at(0))
			->method('getContent')
			->with('file')
			->will($this->returnValue('some text'));
		$this->content
			->expects($this->at(1))
			->method('getContent')
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
		$this->content
			->expects($this->at(0))
			->method('getContent')
			->with('file')
			->will($this->returnValue('some text'));
		$this->content
			->expects($this->at(1))
			->method('getContent')
			->with('subfolder/subdocument')
			->will($this->returnValue('subtext'));
		$this->content
			->expects($this->at(2))
			->method('getContent')
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