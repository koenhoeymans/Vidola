<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_DocumentBuilder_DocumentBuilderTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->docStructure = $this->getMockBuilder('\\Vidola\\Util\\DocumentStructure')
								->disableOriginalConstructor()
								->getMock();
		$this->textReplacer = $this->getMock(
			'\\Vidola\\UnitTests\\Support\\MockTextReplacer', array('replace')
		);
		$this->fileRetriever = $this->getMock('\\Vidola\\Util\\DocFileRetriever');
		$this->pageApi = $this->getMock('\\Vidola\\TemplateApi\\PageApiFactory');

		$this->documentBuilder = new \Vidola\DocumentBuilder\DocumentBuilder(
			$this->docStructure,
			$this->textReplacer,
			$this->getMock('\\Vidola\\TemplateBasedView\\TemplateBasedView'),
			$this->fileRetriever,
			$this->pageApi
		);
	}

	/**
	 * @test
	 */
	public function presentsTextReplacerWithContentsFromSpecifiedFile()
	{
		$file = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'VeryLittleText.vi';

		$this->fileRetriever
			->expects($this->once())
			->method('retrieveContent')
			->with('fileName')
			->will($this->returnValue(file_get_contents($file)));
		$this->textReplacer
			->expects($this->once())
			->method('replace')
			->with(file_get_contents($file));
		$this->docStructure
			->expects($this->once())
			->method('getSubFiles')
			->will($this->returnValue(array()));
		$this->pageApi
			->expects($this->any())
			->method('createWith')
			->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));

		$this->documentBuilder->build('fileName');
	}

	/**
	 * @test
	 */
	public function takesSubdocumentsIntoAccount()
	{
		$file = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'VeryLittleText.vi';

		$this->fileRetriever
			->expects($this->at(0))
			->method('retrieveContent')
			->with('foo')
			->will($this->returnValue('not much text'));
		$this->fileRetriever
			->expects($this->at(1))
			->method('retrieveContent')
			->with('bar')
			->will($this->returnValue('also not much text'));
		$this->textReplacer
			->expects($this->any())
			->method('replace')
			->will($this->returnValue('output'));
		$this->docStructure
			->expects($this->at(2))
			->method('getSubFiles')
			->with('foo')
			->will($this->returnValue(array('bar')));
		$this->docStructure
			->expects($this->at(5))
			->method('getSubFiles')
			->with('bar')
			->will($this->returnValue(array()));
		$this->pageApi
			->expects($this->any())
			->method('createWith')
			->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));

		$this->documentBuilder->build('foo');
	}

	/**
	 * @test
	 */
	public function asksWriterToWriteReplacedTextWithGivenFileName()
	{
		$file = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'VeryLittleText.vi';

		$this->fileRetriever
			->expects($this->once())
			->method('retrieveContent')
			->with('fileName')
			->will($this->returnValue(file_get_contents($file)));
		$this->textReplacer
			->expects($this->once())
			->method('replace')
			->with(file_get_contents($file))
			->will($this->returnValue('output'));
		$this->docStructure
			->expects($this->once())
			->method('getSubFiles')
			->with('fileName')
			->will($this->returnValue(array()));
		$this->pageApi
			->expects($this->any())
			->method('createWith')
			->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));

		$this->documentBuilder->build('fileName');
	}

	/**
	 * @test
	 */
	public function aDirectoryCanBeSpecifiedAsSourceTakingIndexAsInput()
	{
		$dir = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'DirWithMultipleInputFiles';

		$this->fileRetriever
			->expects($this->any())
			->method('retrieveContent')
			->with('Index');
		$this->docStructure
			->expects($this->once())
			->method('getSubFiles')
			->with('Index')
			->will($this->returnValue(array()));
		$this->pageApi
			->expects($this->any())
			->method('createWith')
			->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));

		$this->documentBuilder->build($dir);
	}

	/**
	 * @test
	 */
	public function outputFilesHasSameNameAsInputFile()
	{
		$this->textReplacer
			->expects($this->once())
			->method('replace')
			->will($this->returnValue('some text'));
		$this->docStructure
			->expects($this->any())
			->method('getSubFiles')
			->will($this->returnValue(array()));
		$this->pageApi
			->expects($this->any())
			->method('createWith')
			->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));

		$this->documentBuilder->build('sample');
	}
}