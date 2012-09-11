<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class DocFileRetriever implements ContentRetriever
{
	private $sourceDir = '';

	public function setSourceDir($dir)
	{
		$this->sourceDir = realpath($dir);
	}

	public function retrieve($file)
	{
		if (file_exists($file))
		{
			return file_get_contents($file);
		}

		if (file_exists($file . '.txt'))
		{
			return file_get_contents($file. '.txt');
		}

		if (file_exists($file . '.text'))
		{
			return file_get_contents($file. '.text');
		}

		if (file_exists($this->sourceDir . DIRECTORY_SEPARATOR . $file . '.txt'))
		{
			return file_get_contents(
				$this->sourceDir . DIRECTORY_SEPARATOR . $file . '.txt'
			);
		}

		if (file_exists($this->sourceDir . DIRECTORY_SEPARATOR . $file . '.text'))
		{
			return file_get_contents(
			$this->sourceDir . DIRECTORY_SEPARATOR . $file . '.text'
			);
		}

		$ucFile = ucfirst($file);
		if ($ucFile !== $file)
		{
			return $this->retrieve($ucFile);
		}

		throw new \Exception(
			'DocFileRetriever::retrieveContent() couldn\'t find "' . $file . '"'
		);
	}
}