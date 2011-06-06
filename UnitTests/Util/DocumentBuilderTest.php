<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_DocumentBuilderTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->docStructure = $this->getMockBuilder('\\Vidola\\Util\\DocumentStructure')
								->disableOriginalConstructor()
								->getMock();
		$this->textReplacer = $this->getMock(
			'\\Vidola\\UnitTests\\Support\\MockTextReplacer', array('replace')
		);
		$this->writer = $this->getMock('\\Vidola\\Util\\Writer');
		$this->headers = $this->getMock('\\Vidola\\Patterns\\Header');

		$this->documentBuilder = new \Vidola\Util\DocumentBuilder(
			$this->docStructure, $this->textReplacer, $this->writer, $this->headers
		);
	}

	/**
	 * @test
	 */
	public function presentsTextReplacerWithContentsFromSpecifiedFile()
	{
		// given
		$file = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'VeryLittleText.vi';

		// then
		$this->textReplacer
			->expects($this->once())
			->method('replace')
			->with(file_get_contents($file));
		$this->docStructure
			->expects($this->any())
			->method('getSubFiles')
			->will($this->returnValue(array()));

		// when
		$this->documentBuilder->build($file, 'target.dir');
	}

	/**
	 * @test
	 */
	public function takesSubdocumentsIntoAccount()
	{
		// given
		$file = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'VeryLittleText.vi';

		// then
		$this->docStructure
			->expects($this->once())
			->method('getSubFiles')
			->with(file_get_contents($file))
			->will($this->returnValue(array()));

		// when
		$this->documentBuilder->build($file, 'target.dir');
	}

	/**
	 * @test
	 */
	public function asksWriterToWriteTransformedResultToTargetDestiny()
	{
		// given
		$file = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'VeryLittleText.vi';
		$targetDir = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support';

		// then
		$this->writer
			->expects($this->once())
			->method('write')
			->with('replaced text', substr($file, 0, -2) . 'html');
		$this->textReplacer
			->expects($this->any())
			->method('replace')
			->will($this->returnValue('replaced text'));
		$this->docStructure
			->expects($this->any())
			->method('getSubFiles')
			->will($this->returnValue(array()));

		// when
		$this->documentBuilder->build($file, $targetDir);
	}

	/**
	 * @test
	 */
	public function aDirectoryCanBeSpecifiedAsSourceTakingIndexAsInput()
	{
		// given
		$dir = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'DirWithMultipleInputFiles';
		$file1 = $dir . DIRECTORY_SEPARATOR . 'Index.vi';

		// then
		$this->textReplacer
			->expects($this->once())
			->method('replace')
			->with(file_get_contents($file1));
		$this->docStructure
			->expects($this->any())
			->method('getSubFiles')
			->will($this->returnValue(array()));

		// when
		$this->documentBuilder->build($dir, 'targetDir');
	}

	/**
	 * @test
	 */
	public function outputFilesHasSameNameAsInputFile()
	{
		// given
		$file = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'VeryLittleText.vi';

		// then
		$this->textReplacer
			->expects($this->once())
			->method('replace')
			->will($this->returnValue('some text'));
		$this->writer
			->expects($this->once())
			->method('write')
			->with('some text', __DIR__ . DIRECTORY_SEPARATOR . 'VeryLittleText.html');
		$this->docStructure
			->expects($this->any())
			->method('getSubFiles')
			->will($this->returnValue(array()));

		// when
		$this->documentBuilder->build($file, __DIR__);
	}

	/**
	 * @test
	 */
	public function targetDirCanBeRelative()
	{
		// given
		$file = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'VeryLittleText.vi';

		// then
		$this->textReplacer
			->expects($this->any())
			->method('replace')
			->will($this->returnValue('replaced text'));
		$target = getcwd()
			. DIRECTORY_SEPARATOR . 'relativeDir'
			. DIRECTORY_SEPARATOR . 'VeryLittleText.html';
		$this->writer
			->expects($this->once())
			->method('write')
			->with('replaced text', $target);
		$this->docStructure
			->expects($this->any())
			->method('getSubFiles')
			->will($this->returnValue(array()));

		// when
		$this->documentBuilder->build($file, 'relativeDir');
	}
}