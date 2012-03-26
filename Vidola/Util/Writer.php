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
		$this->outputDir = $dir;
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
		$dir = realpath($this->outputDir);

		if (!$dir)
		{
			throw new \Exception('Directory ' . $this->outputDir . ' not found.');
		}

		$file = $dir . DIRECTORY_SEPARATOR . $fileName . $this->extension;

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