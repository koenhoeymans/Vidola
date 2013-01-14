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
		$destFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename(__FILE__);

		if (file_exists($destFile))
		{
			unlink($destFile);
		}

		$this->fileCopy->copy(__DIR__, sys_get_temp_dir(), basename(__FILE__));

		$this->assertTrue(
			file_get_contents(sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename(__FILE__))
			===
			file_get_contents(__FILE__)
		);

		unlink($destFile);
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
	public function copiesDirectory()
	{
		$targetDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'DirCopy2';
		$baseDir = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support';
		$sourceDir = 'DirCopy2' . DIRECTORY_SEPARATOR;
		$sourceFile = $baseDir . DIRECTORY_SEPARATOR . $sourceDir . 'FileCopy2.php';
		$targetFile = $targetDir . DIRECTORY_SEPARATOR . 'FileCopy2.php';

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

		unlink($targetFile);
		rmdir($targetDir);
	}

	/**
	 * @test
	 */
	public function copyDirectories()
	{
		$baseDir = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support';

		$targetDir1 = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'DirCopy';
		$sourceDir1 = 'DirCopy' . DIRECTORY_SEPARATOR;
		$sourceFile1 = $baseDir . DIRECTORY_SEPARATOR . $sourceDir1 . 'FileCopy.php';
		$targetFile1 = $targetDir1 . DIRECTORY_SEPARATOR . 'FileCopy.php';

		$targetDir2 = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'DirCopy2';
		$sourceDir2 = 'DirCopy2' . DIRECTORY_SEPARATOR;
		$sourceFile2 = $baseDir . DIRECTORY_SEPARATOR . $sourceDir2 . 'FileCopy2.php';
		$targetFile2 = $targetDir2 . DIRECTORY_SEPARATOR . 'FileCopy2.php';

		$targetDir3 = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'DirCopy' . DIRECTORY_SEPARATOR . 'SubDir';
		$sourceDir3 = 'DirCopy' . DIRECTORY_SEPARATOR . 'SubDir' . DIRECTORY_SEPARATOR;
		$sourceFile3 = $baseDir . DIRECTORY_SEPARATOR . $sourceDir3 . 'SubDirFile.php';
		$targetFile3 = $targetDir3 . DIRECTORY_SEPARATOR . 'SubDirFile.php';

		if (file_exists($targetFile3))
		{
			unlink($targetFile3);
		}

		if (is_dir($targetDir3))
		{
			rmdir($targetDir3);
		}

		if (file_exists($targetFile1))
		{
			unlink($targetFile1);
		}

		if (is_dir($targetDir1))
		{
			rmdir($targetDir1);
		}

		if (file_exists($targetFile2))
		{
			unlink($targetFile2);
		}

		if (is_dir($targetDir2))
		{
			rmdir($targetDir2);
		}

		$this->fileCopy->copy($baseDir, sys_get_temp_dir(), array($sourceDir1, $sourceDir2));

		$this->assertTrue(
			file_get_contents($targetFile1) === file_get_contents($sourceFile1)
		);
		$this->assertTrue(
			file_get_contents($targetFile2) === file_get_contents($sourceFile2)
		);
		$this->assertTrue(
			file_get_contents($targetFile3) === file_get_contents($sourceFile3)
		);

		unlink($targetFile3);
		rmdir($targetDir3);
		unlink($targetFile1);
		rmdir($targetDir1);
		unlink($targetFile2);
		rmdir($targetDir2);
	}

	/**
	 * @test
	 */
	public function excludesSpecifiedFilesFromBeingCopied()
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

		$this->fileCopy->copy($source, sys_get_temp_dir(), $file, $file);

		$this->assertFalse(
			file_exists(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file)
		);
	}

	/**
	 * @test
	 */
	public function excludesSpecifiedDirectoryFromBeingCopied()
	{
		$baseDir = __DIR__
			. DIRECTORY_SEPARATOR . '..'
			. DIRECTORY_SEPARATOR . 'Support' . DIRECTORY_SEPARATOR;
		$sourceDir = 'DirCopy' . DIRECTORY_SEPARATOR;
		$sourceFile = $baseDir . $sourceDir . 'FileCopy.php';
		$targetDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'DirCopy' . DIRECTORY_SEPARATOR;
		$targetFile = $targetDir . DIRECTORY_SEPARATOR . 'FileCopy.php';
		$excludedSource = $sourceDir . 'SubDir';
		$excludedTarget = $targetDir . 'SubDir';

		if (file_exists($targetFile))
		{
			unlink($targetFile);
		}

		if (is_dir($targetDir))
		{
			rmdir($targetDir);
		}

		$this->fileCopy->copy($baseDir, sys_get_temp_dir(), $sourceDir, $excludedSource);

		$this->assertTrue(
			file_get_contents($targetFile) === file_get_contents($sourceFile)
		);
		$this->assertFalse(is_dir($excludedTarget));

		unlink($targetFile);
		rmdir($targetDir);
	}
}