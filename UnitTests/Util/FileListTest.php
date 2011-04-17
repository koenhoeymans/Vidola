<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_FileListTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->fileList = new \Vidola\Util\FileList();
	}

	/**
	 * @test
	 */
	public function containsListOfFileNamesPairedToContents()
	{
		//given
		$this->fileList->addFile('sample', 'contents of the file');

		// when
		foreach ($this->fileList->getAll() as $name => $file)
		{
			$fileContained = array($name, $file);
		}

		// then
		$this->assertEquals(
			array('sample', 'contents of the file'),
			$fileContained
		);
	}

	/**
	 * @test
	 */
	public function returnsContentsForFileName()
	{
		//given
		$this->fileList->addFile('sample', 'contents of the file');

		// when
		$contents = $this->fileList->getContents('sample');

		// then
		$this->assertEquals(
			'contents of the file', $contents
		);
	}
}