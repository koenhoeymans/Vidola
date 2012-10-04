<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_MarkdownBasedDocumentationTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->content = $this->getMock('\\Vidola\\Document\\Content');
		$this->structure = $this->getMock('\\Vidola\\Document\\Structure');
		$this->titleCreator = $this->getMock('\\Vidola\\Util\\TitleCreator');

		$this->mdDoc = new \Vidola\Document\MarkdownBasedDocumentation(
			'file',
			$this->content,
			$this->structure,
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
			->method('getParsedContent')
			->will($this->returnValue(new \DomDocument()));
		
		$this->mdDoc->getContent('file');
	}

	/**
	 * @test
	 */
	public function providesParsedContentAsDomDocument()
	{
		$this->content
			->expects($this->once())
			->method('getParsedContent')
			->will($this->returnValue(new \DomDocument()));
		
		$this->mdDoc->getContent('file', true);
	}

	/**
	 * @test
	 */
	public function knowsSubfilesOfGivenFile()
	{
		$this->content
			->expects($this->atLeastOnce())
			->method('getRawContent')
			->with('file')
			->will($this->returnValue('text'));
		$this->structure
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
			->method('getRawContent')
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
			->method('getRawContent')
			->with('file')
			->will($this->returnValue('text'));
		$this->content
			->expects($this->at(1))
			->method('getRawContent')
			->with('subfile')
			->will($this->returnValue('subtext'));
		$this->structure
			->expects($this->at(0))
			->method('getSubfiles')
			->with('text')
			->will($this->returnValue(array('subfile')));
		$this->structure
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
			->method('getParsedContent')
			->with('file')
			->will($this->returnValue(new \DomDocument));
		$this->structure
			->expects($this->atLeastOnce())
			->method('createTocNode')
			->with(new \DomDocument())
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
			->method('getRawContent')
			->with('file')
			->will($this->returnValue('some text'));
		$this->structure
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
			->method('getRawContent')
			->with('file')
			->will($this->returnValue('some text'));
		$this->content
			->expects($this->at(1))
			->method('getRawContent')
			->with('subfolder/subdocument')
			->will($this->returnValue('subtext'));
		$this->structure
			->expects($this->at(0))
			->method('getSubfiles')
			->with('some text')
			->will($this->returnValue(array('subfolder/subdocument')));
		$this->structure
			->expects($this->at(1))
			->method('getSubfiles')
			->with('subtext')
			->will($this->returnValue(array()));
		$this->structure
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
			->method('getRawContent')
			->with('file')
			->will($this->returnValue('some text'));
		$this->content
			->expects($this->at(1))
			->method('getRawContent')
			->with('subfolder/subdocument')
			->will($this->returnValue('subtext'));
		$this->content
			->expects($this->at(2))
			->method('getRawContent')
			->with('nextfile')
			->will($this->returnValue('nexttext'));
		$this->structure
			->expects($this->at(0))
			->method('getSubfiles')
			->with('some text')
			->will($this->returnValue(array('subfolder/subdocument')));
		$this->structure
			->expects($this->at(1))
			->method('getSubfiles')
			->with('subtext')
			->will($this->returnValue(array('nextfile')));
		$this->structure
			->expects($this->at(2))
			->method('getSubfiles')
			->with('nexttext')
			->will($this->returnValue(array()));
		$this->structure
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
		$this->structure
			->expects($this->once())
			->method('createLInk')
			->with('file', 'subfile')
			->will($this->returnValue('file'));

		$this->assertEquals(
			'file', $this->mdDoc->getStartFileLink('subfile')
		);
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
		$this->content
			->expects($this->any())
			->method('getParsedContent')
			->with('file')
			->will($this->returnValue(new \DOMDocument()));

		$this->mdDoc->getContent('file', false);
	}
}