<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_WriterTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->writer = new \Vidola\Util\Writer();

		if (file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fileName'))
		{
			unlink(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fileName');
		}
		if (file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fileName.ext'))
		{
			unlink(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fileName.ext');
		}
	}

	public function teardown()
	{
		$this->setup();
	}

	/**
	 * @test
	 */
	public function usesSpecifiedDirectoryAsDirectoryToWriteTo()
	{
		$this->writer->setOutputDir(sys_get_temp_dir());
		$this->writer->write('text', 'fileName');
		$this->assertTrue(
			file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fileName')
		);
	}

	/**
	 * @test
	 */
	public function usesSpecifiedFileExtensionIfGiven()
	{
		$this->writer->setOutputDir(sys_get_temp_dir());
		$this->writer->setExtension('.ext');
		$this->writer->write('text', 'fileName');
		$this->assertTrue(
			file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fileName.ext')
		);
	}

	/**
	 * @test
	 */
	public function targetDirMustExist()
	{
		chdir('/');
		$this->writer->setOutputDir('tmp/nonExistentDirectory');
		try {
			$this->writer->write('text', 'fileName');
		} catch (\Exception $e) {
			return;
		}
	}
}