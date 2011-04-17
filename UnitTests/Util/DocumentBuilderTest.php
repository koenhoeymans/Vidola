<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_DocumentBuilderTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->textReplacer = $this->getMock(
			'\\Vidola\\UnitTests\\Support\\MockTextReplacer', array('replace')
		);
		$this->writer = $this->getMock('\\Vidola\\Util\\Writer');
		$this->documentBuilder = new \Vidola\Util\DocumentBuilder(
			$this->textReplacer, $this->writer
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

		// when
		$this->documentBuilder->build($file, $targetDir);
	}

	/**
	 * @test
	 */
	public function aDirectoryCanBeSpecifiedAsSourceToTakeAllContainingFilesAsInput()
	{
		// given
		$dir = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'DirWithMultipleInputFiles';
		$file1 = $dir . DIRECTORY_SEPARATOR . 'File1.vi';
		$file2 = $dir . DIRECTORY_SEPARATOR . 'File2.vi';

		// then
		$this->textReplacer
			->expects($this->at(0))
			->method('replace')
			->with(file_get_contents($file1));
		$this->textReplacer
			->expects($this->at(1))
			->method('replace')
			->with(file_get_contents($file2));

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

		// when
		$this->documentBuilder->build($file, __DIR__);
	}

	/**
	 * @test
	 */
	public function whenInputIsMultipleFilesSingleOutputFileCanBeSpecified()
	{
		// given
		$dir = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'DirWithMultipleInputFiles';
		$file1 = $dir . DIRECTORY_SEPARATOR . 'File1.vi';
		$file2 = $dir . DIRECTORY_SEPARATOR . 'File2.vi';

		// then
		$this->textReplacer
			->expects($this->at(0))
			->method('replace')
			->with(file_get_contents($file1) . file_get_contents($file2))
			->will($this->returnValue('single file'));
		$this->writer
			->expects($this->once())
			->method('write')
			->with('single file', __DIR__ . DIRECTORY_SEPARATOR . 'targetFile.html');

		// when
		$this->documentBuilder->build($dir, __DIR__, 'targetFile.html');
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

		// when
		$this->documentBuilder->build($file, 'relativeDir');
	}
}