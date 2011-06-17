<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class Writer
{
	private $outputDir;

	private $extension = '';

	public function setExtension($ext)
	{
		$this->extension = $ext;
	}

	/**
	 * Set output directory to write files to.
	 * 
	 * @param string $dir
	 */
	public function setOutputDir($dir)
	{
		$realpath = realpath($dir);
		if (!$realpath)
		{
			$realpath = realpath(getcwd() . $dir);
		}

		$this->outputDir = $realpath;
	}

	/**
	 * Writes text to a specified file.
	 * 
	 * @param string $text
	 * @param string $fileName
	 * @throws \Exception
	 */
	public function write($text, $fileName)
	{
		$file = $this->outputDir . DIRECTORY_SEPARATOR . $fileName . $this->extension;
		$dir = dirname($file);
		if (!is_dir($dir))
		{
		    mkdir($dir, 0755, true);
		}
		$fileHandle = fopen($file, 'w');

		if (!$fileHandle)
		{
			throw new \Exception('Writer::write() was unable to open ' . $file);
		}

		if(fwrite($fileHandle, $text) === false)
		{
			throw new \Exception('Writer::write() was unable to write to ' . $file);
		}

		fclose($fileHandle);
	}
}