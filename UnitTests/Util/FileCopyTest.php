<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_FileCopyTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->fileCopy = new \Vidola\Util\FileCopy();
	}

	/**
	 * @test
	 */
	public function copiesGivenFileToDestinationDir()
	{
		if (file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename(__FILE__)))
		{
			unlink(sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename(__FILE__));
		}

		$this->fileCopy->copy(__DIR__, sys_get_temp_dir(), basename(__FILE__));

		$this->assertTrue(
			file_get_contents(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'FileCopyTest.php')
			===
			file_get_contents(__FILE__)
		);

		unlink(sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename(__FILE__));
	}

	/**
	 * @test
	 */
	public function copiesFileIntoSubDirs()
	{
		$source = __DIR__
			. DIRECTORY_SEPARATOR . '..';
		$file = 'Util'
			. DIRECTORY_SEPARATOR . basename(__FILE__);

		if (file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file))
		{
			unlink(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file);
		}
		if (is_dir(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Util'))
		{
			rmdir(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Util');
		}

		$this->fileCopy->copy($source, sys_get_temp_dir(), $file);

		$this->assertTrue(
			file_get_contents(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file)
			===
			file_get_contents(__FILE__)
		);
		
		unlink(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file);
		rmdir(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Util');
	}

	/**
	 * @test
	 */
	public function copiesDirectories()
	{
		$targetDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'DirCopy';
		$baseDir = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support';
		$sourceDir = 'DirCopy' . DIRECTORY_SEPARATOR;
		$sourceFile = $baseDir . DIRECTORY_SEPARATOR . $sourceDir . 'FileCopy.php';
		$targetFile = $targetDir . DIRECTORY_SEPARATOR . 'FileCopy.php';

		if (file_exists($targetFile))
		{
			unlink($targetFile);
		}

		if (is_dir($targetDir))
		{
			rmdir($targetDir);
		}

		$this->fileCopy->copy($baseDir, sys_get_temp_dir(), $sourceDir);

		$this->assertTrue(
			file_get_contents($targetFile) === file_get_contents($sourceFile)
		);

		if (file_exists($targetFile))
		{
			unlink($targetFile);
		}

		if (is_dir($targetDir))
		{
			rmdir($targetDir);
		}
	}
}