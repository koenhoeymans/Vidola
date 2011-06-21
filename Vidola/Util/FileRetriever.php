<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class FileRetriever
{
	private $sourceDir = '';

	public function setSourceDir($dir)
	{
		$this->sourceDir = realpath($dir);
	}

	public function retrieveContent($file)
	{
		$file = ucfirst($file);

		if (file_exists($file . '.vi'))
		{
			return file_get_contents($file. '.vi');
		}

		if (file_exists($this->sourceDir . DIRECTORY_SEPARATOR . $file . '.vi'))
		{
			return file_get_contents(
				$this->sourceDir . DIRECTORY_SEPARATOR . $file . '.vi'
			);
		}

		throw new \Exception('FileRetriever::retrieveContent() couldn\'t find ' . $file);
	}
}