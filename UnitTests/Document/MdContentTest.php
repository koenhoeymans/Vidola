<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_MdContentTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->parser = $this->getMock('\\Vidola\\Parser\\Parser');
		$this->retriever = $this->getMock('\\Vidola\\Util\\ContentRetriever');
		$this->content = new \Vidola\Document\MdContent($this->parser, $this->retriever);
	}

	/**
	 * @test
	 */
	public function providesParsedContentOfFile()
	{
		$this->parser
			->expects($this->once())
			->method('parse')
			->will($this->returnValue(new \DomDocument()));

		$this->content->getContent('file');
	}

	/**
	 * @test
	 */
	public function providesRawContentOfFile()
	{
		$this->retriever
			->expects($this->once())
			->method('retrieve')
			->will($this->returnValue('bar'));
		$this->parser
			->expects($this->never())
			->method('parse')
			->will($this->returnValue(new \DomDocument()));
		
		$this->content->getContent('file', false);
	}

	/**
	 * @test
	 */
	public function cachesParsedContent()
	{
		$this->retriever
			->expects($this->any())
			->method('retrieve')
			->will($this->returnValue('foo'));
		$this->parser
			->expects($this->once())
			->method('parse')
			->will($this->returnValue(new \DomDocument()));

		$this->content->getContent('file', true);
		$this->content->getContent('file', true);
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
		$this->content->addPostTextProcessor($postProcessor);

		$this->parser
			->expects($this->once())
			->method('parse')
			->will($this->returnValue(new \DomDocument()));

		$this->content->getContent('file', true);
	}
}