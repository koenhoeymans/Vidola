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
	 * @param string $fileName Relative to output directory.
	 * @throws \Exception
	 */
	public function write($text, $fileName)
	{
		$dir = $this->outputDir . DIRECTORY_SEPARATOR . substr(
			$fileName, 0, strrpos($fileName, DIRECTORY_SEPARATOR)
		);
		$fileName = substr($fileName, strrpos($fileName, DIRECTORY_SEPARATOR));

		if (!is_dir($dir))
		{
			mkdir($dir);
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