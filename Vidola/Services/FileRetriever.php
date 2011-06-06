<?php

/**
 * @package Vidola
 */
namespace Vidola\Services;

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
		return file_get_contents($this->sourceDir . DIRECTORY_SEPARATOR . $file);
	}
}