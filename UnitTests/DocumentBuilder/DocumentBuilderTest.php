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
		$this->outputBuilder = $this->getMock('\\Vidola\\OutputBuilder\\OutputBuilder');
		$this->headers = $this->getMock('\\Vidola\\Pattern\\Patterns\\Header');
		$this->fileRetriever = $this->getMock('\\Vidola\\Util\\FileRetriever');

		$this->documentBuilder = new \Vidola\DocumentBuilder\DocumentBuilder(
			$this->docStructure,
			$this->textReplacer,
			$this->outputBuilder,
			$this->headers,
			$this->fileRetriever
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
			->with('fileName')
			->will($this->returnValue(file_get_contents($file)));
		$this->fileRetriever
			->expects($this->at(1))
			->method('retrieveContent')
			->with('subfile');
		$this->textReplacer
			->expects($this->any())
			->method('replace')
			->will($this->returnValue('output'));
		$this->docStructure
			->expects($this->at(0))
			->method('getSubFiles')
			->with('fileName')
			->will($this->returnValue(array('subfile')));
		$this->docStructure
			->expects($this->at(1))
			->method('getSubFiles')
			->with('subfile')
			->will($this->returnValue(array()));

		$this->documentBuilder->build('fileName');
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
		$this->outputBuilder
			->expects($this->any())
			->method('build');

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
		$this->outputBuilder
			->expects($this->once())
			->method('build');
		$this->docStructure
			->expects($this->any())
			->method('getSubFiles')
			->will($this->returnValue(array()));

		$this->documentBuilder->build('sample');
	}
}