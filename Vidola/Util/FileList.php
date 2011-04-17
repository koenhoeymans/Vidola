<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class FileList
{
	/**
	 * $files = array('fileName' => 'contents');
	 * 
	 * @var array
	 */
	private $files = array();

	/**
	 * @param string $name
	 * @param string $contents
	 * @return FileList
	 */
	public function addFile($name, $contents)
	{
		$this->files[$name] = $contents;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getAll()
	{
		return $this->files;
	}

	/**
	 * @param string $fileName
	 * @return string
	 */
	public function getContents($fileName)
	{
		if (!isset($this->files[$fileName]))
		{
			return null;
		}

		return $this->files[$fileName];
	}
}