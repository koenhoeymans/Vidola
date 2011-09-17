<?php

/**
 * @package Vidola
 */
namespace Vidola\Util;

/**
 * @package Vidola
 */
class RelativeUrlBuilder
{
	private $extension = '';

	/**
	 * Set extension of files to link to.
	 * 
	 * @param string $extension
	 */
	public function setExtension($extension)
	{
		$this->extension = $extension;
	}

	/**
	 * Build relative url for given documentation file.
	 * 
	 * @param string $relativeFile
	 * @return string
	 */
	public function buildUrl($relativeFile)
	{
		$numberSignPos = strpos($relativeFile, "#");

		if ($numberSignPos === false)
		{
			$filePart = $relativeFile;
			$relPart = '';
		}
		else
		{
			$filePart = substr($relativeFile, 0, $numberSignPos);
			$relPart = substr($relativeFile, $numberSignPos);
		}

		if (!$this->extension)
		{
			return $filePart;
		}

		return $filePart . '.' . $this->extension . $relPart;
	}
}