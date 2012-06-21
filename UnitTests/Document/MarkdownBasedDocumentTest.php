<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_MarkdownBasedDocumentTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->contentRetriever = $this->getMock('\\Vidola\\Util\\ContentRetriever');
		$this->parser = $this->getMock('\\Vidola\\Parser\\Parser');
		$this->subfileDetector = $this->getMock('\\Vidola\\Util\\SubfileDetector');
		$this->internalLinkBuilder = $this->getMock('\\Vidola\\Util\\InternalLinkBuilder');
		$this->mdDoc = new \Vidola\Document\MarkdownBasedDocument(
			'file',
			$this->contentRetriever,
			$this->parser,
			$this->subfileDetector,
			$this->internalLinkBuilder
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
	public function createsFilename()
	{
		$this->assertEquals('File', $this->mdDoc->getFilename('/tmp/File.html'));
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
}